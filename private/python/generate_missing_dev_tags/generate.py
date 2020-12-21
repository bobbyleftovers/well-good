#!/usr/bin/python

'''
To run from command line:

python private/python/generate_missing_dev_tags/generate.py --cat=good-food

Arguments:
LEGACY CATEGORY
--cat=<string: legacy category slug>
  default = ''

RESET
--reset=<true/false>
  default = True
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

from private.python.generate_missing_dev_tags.fetch_data import Fetch_Data
from private.python.managers.log_manager import log_update, log_progress_bar


class Generate():
  target = os.environ['TARGET_SITE']
  post_request_path = 'wp-json/wellandgood/v1/generate-legacy-category-dev-tag'

  def __init__(self, cat='', reset_progress=True):
    self.data = Fetch_Data(cat, reset_progress)
    self.legacy_category = cat
    self.data.check_progress_data(reset_progress)

    self.progress = self.data.open_data_files('progress')



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

    self.data.check_input_files()
    
    ## Open data files
    self.list_posts = self.data.open_data_files('posts')

    ## Loop through all posts
    total_posts = len(self.list_posts)
    
    log_update(f'Starting conversion at {datetime.now().strftime("%H:%M:%S")}')
    
    index = 0
    errored_posts = []
    for post in self.list_posts:
      index += 1

      post_id = int(post['id'])
      target = post['legacy_category']

      if post_id in self.progress:
        continue

      if target != self.legacy_category:
        errored_posts.append({
          'ID': post_id,
          'Reason': 'Legacy category doesn\'t match' })
        continue

      try:
        data = {
          'post': post_id,
          'dev_tag': f'legacy-category-{target}' }

        headers = { 'Content-Type': 'application/json' }
        r = requests.post(f'{self.target}/{self.post_request_path}', json=data, headers=headers)


      except (requests.exceptions.ConnectionError, requests.exceptions.Timeout) as e:
        post_request = None
        errored_posts.append({
          'ID': post_id,
          'Reason': 'requests.exceptions.ConnectionError, requests.exceptions.Timeout' })
        print("requests.exceptions.ConnectionError, requests.exceptions.Timeout")
        print(e)
        pass
      
      except (ValueError, requests.exceptions.HTTPError, urllib.error.URLError) as e:
        post_request = None
        errored_posts.append({
          'ID': post_id,
          'Reason': 'ValueError, requests.exceptions.HTTPError, urllib.error.URLError' })
        print("ValueError, requests.exceptions.HTTPError, urllib.error.URLError")
        print(e)
        pass
      
      except Exception as e:
        post_request = None
        errored_posts.append({
          'ID': post_id,
          'Reason': 'Exception' })
        print("Exception")
        print(e)
        pass 

      log_progress_bar(
        index, 
        total_posts, 
        f'Updating post {post_id}: {index} of {total_posts}', 
        'All posts updated')

      self.update_progress_file(post_id)

    log_update(f'Conversion finished at {datetime.now().strftime("%H:%M:%S")}')

    if len(errored_posts):
      print('Errors occrued on posts:')
      for post in errored_posts:
        print(post)

    if self.data.temp_dir_exists:
      self.data.remove_directory('temp')



def main(args):
  transfer = Generate(args.cat, args.reset)
  transfer.run()


if __name__ == "__main__":
  parser = argparse.ArgumentParser()

  parser.add_argument("--cat",
                      default='',
                      type=str,
                      help="Legacy Category to transfer to dev_tag?")

  parser.add_argument("--reset",
                      default=True,
                      type=bool,
                      help="Reset progress?")
                      
  main(parser.parse_args())