#!/usr/bin/python

'''
To run from command line:

python private/python/editorialtag_conversion/update_editorialtags.py

Arguments:
UPDATE
--reset=<true/false>
  default = False
'''


import os
import re
import csv
import json
import requests
import argparse
import urllib.error
import urllib.parse
import urllib.request
from datetime import datetime

from private.python.editorialtag_conversion.fetch_data import Fetch_Data
from private.python.managers.log_manager import log_update, log_progress_bar


class Update_Editorialtags():
  target = os.environ['TARGET_SITE']

  def __init__(self, reset_progress=False):
    self.data = Fetch_Data()

    self.data.check_progress_data(reset_progress)
    self.total_post_conversions = 0
    self.total_post_updates = 0

    self.results_path = f'{self.data.conversion_root}/{self.data.results_path}'
    self.list_categories = json.load(open(self.data.get_file_path('categories')))

    self.progress = self.data.open_data_files('progress')
    self.results = self.get_results_file()

    with open(self.data.get_file_path(
             'results', False, 
             self.results), 'r') as f:
      self.posts = list(csv.reader(f, delimiter=','))
      self.posts.pop(0)
      self.total_post_updates = len(self.posts)



  def get_results_file(self):
    '''
    '''

    results_file = max(list(filter(
      re.compile('.+-RESULTS.csv').match,
      os.listdir(f'{self.results_path}'))))
    
    with open(f'{self.results_path}/{results_file}') as csv_counter:
      self.total_post_conversions = len(list(csv.DictReader(csv_counter)))
    
    dev_file = f'{results_file.split("-")[0]}-RESULTS--dev.csv'
    dev_file_exists = True if os.path.exists(f'{self.results_path}/{dev_file}') else False

    if not dev_file_exists:
      self.convert_results(results_file, dev_file)

    return dev_file



  def convert_results(self, results_file, dev_file):
    '''
    '''

    data = []
    with open(f'{self.results_path}/{results_file}') as csv_file:
      csv_reader = csv.DictReader(csv_file)
      for index, row in enumerate(csv_reader, start=1):
        post_link = row['Link'] \
          if row.get('Link') != None else ''
        hero_tag = row['Hero Tag'] \
          if row.get('Hero Tag') != None else ''
        tag_1 = row['Tag 1'] \
          if row.get('Tag 1') != None else ''
        tag_2 = row['Tag 2'] \
          if row.get('Tag 2') != None else ''
        backend_tags = row['Backend Tags'] \
          if row.get('Backend Tags') != None else ''
        post_id = row['post_id'] \
          if row.get('post_id') != None else ''
        legacy_categories = row['Legacy Category'] \
          if row.get('Legacy Category') != None else ''

        post_slug = urllib.parse.urlparse(post_link).path[:-1].split('/')[-1]
        
        conv_hero_tag = ''
        conv_tag_1 = ''
        conv_tag_2 = ''
        for key, category in self.list_categories.items():
          if hero_tag == category['name']:
            conv_hero_tag = key

          if tag_1 == category['name']:
            conv_tag_1 = key

          if tag_2 == category['name']:
            conv_tag_2 = key

          if conv_hero_tag and conv_tag_1 and conv_tag_2:
            break

        split_backend_tags = backend_tags.split(', ') if backend_tags else None
        conv_backend_tags = '|'.join(split_backend_tags) if split_backend_tags else ''

        errors = []
        if post_id and legacy_categories:
          conv_post_id = post_id
          conv_legacy_category = legacy_categories

        else:
          try:
            post_request = requests.get(f'{self.target}/wp-json/wp/v2/posts/?slug={post_slug}').json()

          except (requests.exceptions.ConnectionError, requests.exceptions.Timeout) as e:
            post_request = None
            errors.append("ConnectionError/Timeout")
            pass
          
          except (ValueError, requests.exceptions.HTTPError, urllib.error.URLError) as e:
            post_request = None
            errors.append("HTTPError/URLError")
            pass
          
          except Exception as e:
            post_request = None
            errors.append("exception")
            pass 
          
          if post_id:
            conv_post_id = post_id
          else:
            conv_post_id = post_request[0]['id'] if post_request and len(post_request) > 0 and post_request[0] else None

          if legacy_categories:
            conv_legacy_category = legacy_categories
          else:
            legacy_category_matches = []
            legacy_category = post_request[0]['categories'] if post_request and len(post_request) > 0 and post_request[0] else []
            matches = ['good-advice',
                       'good-food',
                       'good-home',
                       'good-looks',
                       'good-sweat',
                       'good-travel']
            for legacy_category_id in legacy_category:
              if self.list_categories[str(legacy_category_id)]['slug'] in matches:
                legacy_category_matches.append(self.list_categories[str(legacy_category_id)]['slug'])
              
            conv_legacy_category = '|'.join(legacy_category_matches)

        if conv_post_id:
          data.append([
            conv_post_id,
            conv_hero_tag,
            conv_tag_1,
            conv_tag_2,
            conv_legacy_category,
            conv_backend_tags
          ])

        progress_message = f'Updating CSV post {conv_post_id}: {index} of {self.total_post_conversions}' if len(errors) == 0 else f'Updating CSV post {conv_post_id}: {index} of {self.total_post_conversions}'
        success_message = 'All CSV posts successfully converted' if len(errors) == 0 else f'CSV posts converted with {len(errors)} errors'

        log_progress_bar(
          index, 
          self.total_post_conversions, 
          progress_message, 
          success_message)
        
    with open(f'{self.results_path}/{dev_file}', 'w') as new_dev_file:
      dev_writer = csv.writer(new_dev_file, delimiter=',', quotechar='"', quoting=csv.QUOTE_MINIMAL)

      dev_writer.writerow(['post_id', 'hero_tag', 'tag_1', 'tag_2', 'legacy_category', 'backend_tag'])
      for entry in data:
        dev_writer.writerow(entry)



  def update_progress_file(self, post_id):
    '''
    '''

    self.progress.append(post_id)

    with open(self.data.get_file_path('progress'), 'w') as updated_file:
      updated_file.write(json.dumps(self.progress))

    self.progress = self.data.open_data_files('progress')



  def run(self):
    '''
    '''
    
    log_update(f'Starting conversion at {datetime.now().strftime("%H:%M:%S")}')
    
    index = 0
    for post in self.posts:
      index += 1

      post_id = post[0]
      hero_tag = post[1]
      tag_1 = post[2]
      tag_2 = post[3]
      legacy_category = post[4]
      backend_tags = post[5]

      # if post_id != str(9431934):
      #   continue

      if post_id in self.progress:
        continue

      categories = []
      if hero_tag:
        categories.append(int(hero_tag))
    
      if tag_1:
        categories.append(int(tag_1))

      if tag_2:
        categories.append(int(tag_2))

      hero_parent = self.list_categories[str(hero_tag)]['parent'] if hero_tag else 0
      while hero_parent != 0:
        categories.append(hero_parent)

        parent = self.list_categories[str(hero_parent)]['parent']
        hero_parent = parent

      for cat_id in self.list_categories:
        if self.list_categories[cat_id]['slug'] in legacy_category.split('|'):
          categories.append(int(cat_id))
    
      try:
        data = {
          'categories': categories,
          'hero_tag': int(hero_tag) if hero_tag else '',
          'tag_1': int(tag_1) if tag_1 else '',
          'tag_2': int(tag_2) if tag_2 else '',
          'backend_tag': backend_tags,
          'legacy_category': legacy_category}

        headers = {
          'Content-Type': 'application/json',
          'Authorization': os.environ['ENCODED_LOGIN']}

        r = requests.post(f'{self.target}/wp-json/wp/v2/posts/{post_id}?skip_apple_news=true', json=data, headers=headers)


      except (requests.exceptions.ConnectionError, requests.exceptions.Timeout) as e:
        post_request = None
        print("******************")
        print("requests.exceptions.ConnectionError, requests.exceptions.Timeout")
        print(e)
        print("Errored post: " + post_id)
        pass
      
      except (ValueError, requests.exceptions.HTTPError, urllib.error.URLError) as e:
        post_request = None
        print("******************")
        print("ValueError, requests.exceptions.HTTPError, urllib.error.URLError")
        print(e)
        print("Errored post: " + post_id)
        pass
      
      except Exception as e:
        post_request = None
        print("******************")
        print("Exception")
        print(e)
        print("Errored post: " + post_id)
        pass 

      log_progress_bar(
        index, 
        self.total_post_updates, 
        f'Updating post {post_id}: {index} of {self.total_post_updates}', 
        'All posts updated')

      self.update_progress_file(post_id)

    log_update(f'Conversion finished at {datetime.now().strftime("%H:%M:%S")}')


def main(args):
  update = Update_Editorialtags(args.reset)
  update.run()


if __name__ == "__main__":
  parser = argparse.ArgumentParser()

  parser.add_argument("--reset",
                      default=False,
                      type=bool,
                      help="Reset progress?")
                      
  main(parser.parse_args())