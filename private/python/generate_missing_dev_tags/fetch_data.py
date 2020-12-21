import os
import re
import ast
import csv
import json
import shutil
import certifi
import urllib.request
import dateutil.parser
from datetime import datetime

from private.python.managers.log_manager import log_update, log_progress_bar


class Fetch_Data():
  target_site = os.environ['TARGET_SITE']
  run_time = datetime.now().strftime("%Y%m%d%H%M%S")

  ## Iterators

  ## Folders
  conversion_root = './private/python/generate_missing_dev_tags'
  temp_path = '.temp'
  data_path = '_data'
  posts_file_json = 'data-posts.json'
  progress_file_json = 'data-progress.json'

  fetch_posts_link = f'{target_site}/wp-json/wellandgood/v1/generate-legacy-category-dev-tag'

  temp_dir_exists = os.path.exists(f'{conversion_root}/{temp_path}')
  data_dir_exists = os.path.exists(f'{conversion_root}/{data_path}')


  def __init__(self, cat=None, update_data=True):
    self.update_data = update_data
    self.legacy_category = cat
    self.url_args = f'/?legacyCategory={self.legacy_category}' \
      if self.legacy_category else ''

    self.file_paths = self.compile_paths([
      'posts',
      'progress'])

    self.check_directories()


  def save_to_file(self, fn, row, fieldnames):
    '''
    '''

    if (os.path.isfile(fn)):
      m = 'a'
    else:
      m = 'w'

    with open(fn, m, encoding='utf8', newline='') as csvfile: 
      writer = csv.DictWriter(csvfile, fieldnames=fieldnames)
      if (m == 'w'):
        writer.writeheader()
      writer.writerow(row)


  def check_directories(self):
    '''
    Check if the temp directory and the
    data directory exists, if not, create
    them
    '''

    if not self.temp_dir_exists:
      self.create_directory('temp')
    
    if not self.data_dir_exists:
      self.create_directory('data')


  def create_directory(self, dir):
    '''
    The temporary directory is used to compile data
    while fetching from Wordpress API, it is created
    upon initiation of the fetch_tags or fetch_posts
    functions

    The data directory is used to store formatted data
    after fetching from Wordpress API, it is created
    upon initiation of the fetch_tags or fetch_posts
    functions
    '''

    path = None
    if dir == 'temp':
      path = f'{self.conversion_root}/{self.temp_path}'
    elif dir == 'data':
      path = f'{self.conversion_root}/{self.data_path}'
    
    if path:
      try:
        os.mkdir(path)

        if dir == 'temp':
          self.temp_dir_exists = True
        elif dir == 'data':
          self.data_dir_exists = True

        log_update(f'{dir.capitalize()} directory created') 

      except FileExistsError:
        log_update(f'{dir.capitalize()} directory exists')


  def remove_directory(self, dir):
    '''
    The temporary directory is used to compile data
    while fetching from Wordpress API, it is deleted
    after running the convert_tags function
    '''

    path = None
    if dir == 'temp':
      path = f'{self.conversion_root}/{self.temp_path}'
    

    if path and os.path.exists(path):
      try:
        shutil.rmtree(f'{self.conversion_root}/{self.temp_path}')
        
        if dir == 'temp':
          self.temp_dir_exists = False

        log_update(f'{dir.capitalize()} directory removed')

      except OSError as e:
        log_update(f'Error creating temp directory: {e.filename} - {e.strerror}')


  def compile_paths(self, paths):
    '''
    '''

    file_paths = {}
    for path in paths:
      file_paths[path] = self.get_file_path(path)

    return file_paths


  def fetch_posts(self):
    '''
    This is called from check_posts_data if the file 
    'conversion-data/posts-json.txt' doesn't exist
    '''

    try:
      with urllib.request.urlopen(
        self.fetch_posts_link + 
        self.url_args, cafile=certifi.where()) as res:
        data = res.read()
        total_pages = len(json.loads(data))

    except (urllib.error.HTTPError, urllib.error.URLError) as e:
      data = None

    if data:	
      path = self.get_file_path('posts', True)
      file_ = open(path, 'wb')
      file_.write(data)
      file_.close()

      with open(path) as json_file:
        json_data = json.load(json_file)

    else:
      json_data = None

    if json_data:
      for n in json_data:
        self.temp_post_data.append({
          "id": n['id'],
          "legacy_category": n['legacy_category']
        })
      
      path = self.get_file_path('posts')
      file_ = open(path, 'w')
      file_.write(json.dumps(self.temp_post_data))
      file_.close()



  def check_posts_data(self):
    '''
    Check if the posts data exists, if not,
    fetch the posts
    '''

    posts_file = self.get_file_path('posts')
    file_exists = False
    recompile_posts = True

    if os.path.exists(posts_file):
      file_exists = True

      with open(posts_file, 'r') as json_posts, \
        urllib.request.urlopen(
          self.fetch_posts_link +
          self.url_args, cafile=certifi.where()) as res:
        r = json_posts.read()
        temp_post_data = ast.literal_eval(r)

    if not file_exists or \
      (recompile_posts and self.update_data):
      self.temp_post_data = []
      self.fetch_posts()
      
    else:
      if file_exists:
        log_update('Loading "_data/data-posts.json"')
      else:
        log_update('Missing "_data/data-posts.json"')



  def check_progress_data(self, reset_progress):
    '''
    Make sure "_data/data-progress.json" exists
    '''

    progress_file = self.get_file_path('progress')
    
    reset = []
    if os.path.exists(progress_file):
      if reset_progress:
        log_update(f'Resetting "{self.progress_file_json}"')

        os.remove(progress_file)
        with open(progress_file, 'w') as outfile:
          outfile.write(json.dumps(reset))

      else:
        log_update(f'Loading "{self.progress_file_json}"')
        
    else:
      with open(progress_file, 'w') as outfile:
        outfile.write(json.dumps(reset))
      


  def check_input_files(self):
    '''
    Check if all the necessary files required for
    the conversion process exist
    '''

    self.check_posts_data()



  def open_data_files(self, path):
    '''
    Open data file
    '''

    data = None
    
    with open(self.file_paths[path], 'r') as f:
      data = json.load(f)

    return data



  def get_file_path(self, target, temp=False, file_name=None):
    '''
    Build file path based on class variables
    '''
    file_path = ''
    if target == 'posts':
      file_path += f'{self.conversion_root}/'
      if temp: 
        file_path += f'{self.temp_path}/{target}.txt'
      else:
        file_path += f'{self.data_path}/{self.posts_file_json}'

    elif target == 'progress':
      file_path += f'{self.conversion_root}/{self.data_path}/{self.progress_file_json}'

    return file_path
