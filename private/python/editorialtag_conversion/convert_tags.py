#!/usr/bin/python

'''
To run from command line:

python private/python/editorialtag_conversion/convert_tags.py

Arguments:
UPDATE
--update=<true/false>
  default = False

DATE
--date=<yyyy-mm-dd>
  default = None
'''

import os
import ast
import csv
import json
import html
import argparse
from datetime import datetime

from private.python.editorialtag_conversion.fetch_data import Fetch_Data
from private.python.managers.log_manager import log_update, log_progress_bar


class Convert_Tags():
  ## Data
  list_posts = None
  list_tags = None
  list_categories = None
  list_conversion = None
  list_score = None


  def __init__(self, update_data=True, date=None):
    self.data = Fetch_Data(update_data, date)

    self.current_post = None
    self.run_time = datetime.now().strftime("%Y%m%d%H%M%S")
    self.file_paths = self.data.file_paths


  def get_category_depth(self, category):
    '''
    Check if category is a primary category, a
    sub-category, or a sub-sub-category
    '''

    depth = 0
    parent = category['parent'] if category['parent'] else 0
    if parent > 0:
      depth += 1
      
      grandparent = int(self.list_categories[str(parent)]['parent']) if str(parent) in self.list_categories else 0
      if grandparent > 0:
        depth += 1

    return depth


  def set_conversion_option(self, key, status):
    '''
    Add a category into the post dict. 
    These categories serve as potential categories that might 
    be selected based on the algorithm that selects the 
    categories to assign to a post.
    '''

    if 'conversion_options' not in self.current_post:
      self.current_post['conversion_options'] = {}
  
    if key not in self.current_post['conversion_options']:
      category = self.list_categories[key]
      name = category['name']
      slug = category['slug']
      depth = self.get_category_depth(category)

      self.current_post['conversion_options'][key] = {
        'name': name,
        'slug': slug,
        'depth': depth,
        'status': status,
        'instance': 1}

    else:
      self.current_post['conversion_options'][key]['instance'] += 1


  def set_conversion_warning(self, message):
    '''
    While factoring each of a post's original tags,
    various warnings may be applied to warn the W+G
    editors of potential conflicts or problems with
    the conversion of the tags
    '''

    if 'conversion_warnings' not in self.current_post:
      self.current_post['conversion_warnings'] = []

    if message not in self.current_post['conversion_warnings']:
      self.current_post['conversion_warnings'].append(message)


  def find_score_from_csv(self, category_name):
    '''
    '''

    score = None
    if category_name in self.list_score:
      score = self.list_score[category_name]

    return score


  def set_category_priority(self):
    '''
    '''

    for key, values in self.current_post['conversion_options'].items():
      score = 0
      category = self.list_categories[key]
      
      ## Add instances
      score += values['instance']

      ## Add 2 if cat has a parent in convert_cats
      for option in self.current_post['conversion_options']:
        if category['parent'] == option:
          score *= 2
          break

      ## Multiply by CSV score
      multiplier = self.find_score_from_csv(values['name'])
      if multiplier:
        score *= multiplier

      ## Add category depth * 2
      score *= (values['depth'] * 2)

      ## Multiply by 2 if exact-match
      if values['status'] == 'exact-match':
        score *= 2

      self.current_post['conversion_options'][key]['score'] = score


  def check_errors(self):
    '''
    '''

    counts = {
      'l1': 0,
      'l2': 0}

    for key, values in self.current_post['conversion_options'].items():
      if values['depth'] == 1:
        counts['l1'] += 1

      elif values['depth'] == 2:
        counts['l2'] += 1

      if counts['l1'] > 1:
        self.set_conversion_warning('Hero tag conflict')



  def sort_options(self):
    '''
    '''

    lis = []
    for key, value in self.current_post['conversion_options'].items():
      dictionary = {'id': key}
      dictionary.update(value)
      lis.append(dictionary)
      
    self.current_post['conversion_options'] = sorted(lis, key = lambda i: i['score'], reverse=True)
    

  def convert_tags(self):
    '''
    '''

    self.data.check_directories()
    self.data.check_input_files()
    
    ## Open data files
    self.list_posts = self.data.open_data_files('posts')
    self.list_tags = self.data.open_data_files('tags')
    self.list_categories = self.data.open_data_files('categories')
    self.list_conversion = self.data.open_data_files('conversion')
    self.list_score = self.data.open_data_files('score')

    ## Loop through all posts
    total_posts = len(self.list_posts)
    for i, post in enumerate(self.list_posts, start=1):
      self.current_post = post
      original_tags = []
      backend_tags = []
      legacy_tags = []
      legacy_category = []
      franchise_tags = []
      csv_data = {}

      ## Convert categories to legacy category column
      for category in self.current_post['category']:
        cat_data = self.list_categories[str(category)]
        cat_slug = cat_data['slug']

        legacy_category.append(cat_slug)

      ## Loop through original tags to convert them
      #  to new categories
      for tag in self.current_post['tags']:
        tag_data = self.list_tags[str(tag)]
        tag_name = html.unescape(tag_data['name'])
        tag_slug = tag_data['slug']

        ## Log original tags for CSV
        original_tags.append(tag_name)

        ## First, check if the tag matches one of the 
        #  existing categories
        status = 'exact-match'
        exact_match = None
        for key, values in self.list_categories.items():
          if tag_name.lower() == values['name'].lower():
            exact_match = key
            break

        if exact_match:
          self.set_conversion_option(exact_match, status)

        ## Next, check if the tag is listed in the 
        #  conversion data
        status = 'conversion'
        conversion_data = None
        conv1_match = None
        conv2_match = None
        if tag_slug in self.list_conversion:
          conversion_data = self.list_conversion[tag_slug]
          if conversion_data['conversion1']:
            for key, values in self.list_categories.items():
              if conversion_data['conversion1'] == values['name']:
                conv1_match = key
                break

          if conversion_data['conversion2']:
            for key, values in self.list_categories.items():
              if conversion_data['conversion2'] == values['name']:
                conv2_match = key
                break
          
          ## If the conversion data lists a tag as a backend_tag,
          #	 add to backend_tags
          if conversion_data['backend_tag']:
            backend_tags.append(tag_slug)

          ## If the conversion data lists a tag as a franchise_tag,
          #	 add to franchise_tags
          if conversion_data['franchise_tag']:
            franchise_tags.append(tag_slug)

        ## Add conversion matches to conversion_options
        if conv1_match:
          self.set_conversion_option(conv1_match, status)

        if conv2_match:
          self.set_conversion_option(conv2_match, status)

        ## Last, create a column for legacy tags
        if not exact_match and conversion_data:
          if not conversion_data['backend_tag']:
            legacy_tags.append(tag_name)

      ## Set priorities and check for errors
      if 'conversion_options' in self.current_post:
        valid_conversion = True

        self.set_category_priority()
        self.check_errors()
        self.sort_options()
        
        hero_tag = self.current_post['conversion_options'][0] \
          if len(self.current_post['conversion_options']) > 0 else None

        tag1 = self.current_post['conversion_options'][1] \
          if len(self.current_post['conversion_options']) > 1 else None

        tag2 = self.current_post['conversion_options'][2] \
          if len(self.current_post['conversion_options']) > 2 else None

      else:
        valid_conversion = False
        hero_tag = None
        tag1 = None
        tag2 = None

        self.set_conversion_warning('No conversion options')

      ## Last, set CSV data
      ## Data for client-facing CSV
      client_data = {
        'post_id': self.current_post['id'],
        'Link': self.current_post['link'],
        'Hero Tag': hero_tag['name'] if hero_tag else None,
        'Tag 1': tag1['name'] if tag1 else None,
        'Tag 2': tag2['name'] if tag2 else None,
        'Original Tags': ', '.join(original_tags),
        'Backend Tags': ', '.join(backend_tags),
        'Franchise Tags': ', '.join(franchise_tags),
        'Legacy Tags': ', '.join(legacy_tags),
        'Legacy Category': ', '.join(legacy_category),
        'Warnings': ', '.join(self.current_post['conversion_warnings']) \
          if 'conversion_warnings' in self.current_post else None}
        
      self.data.save_to_file(
        self.file_paths['results'], 
        client_data, 
        client_data.keys()) 

      ## Data for dev CSV
      if valid_conversion:
        dev_data = {
          'post_id': self.current_post['id'],
          'hero_tag': hero_tag['id'] if hero_tag else None,
          'tag_1': tag1['id'] if tag1 else None,
          'tag_2': tag2['id'] if tag2 else None,
          'legacy_category': '|'.join(legacy_category),
          'backend_tag': '|'.join(backend_tags),
          'franchise_tag': '|'.join(franchise_tags)}

        self.data.save_to_file(
          self.file_paths['dev-results'], 
          dev_data, 
          dev_data.keys()) 

      log_progress_bar(
        i, 
        total_posts, 
        'Converting tags', 
        'Tag conversion complete')
      
    ## Delete temporary directory
    if self.data.temp_dir_exists:
      self.data.remove_directory('temp')


def main(args):
  convert = Convert_Tags(args.update, args.date)
  convert.convert_tags()


if __name__ == "__main__":
  parser = argparse.ArgumentParser()

  parser.add_argument("--update",
                      default=False,
                      type=bool,
                      help="Update the data files?")

  parser.add_argument("--date",
                      default=None,
                      type=str,
                      help="Limit to a start date?")
                      
  main(parser.parse_args())
