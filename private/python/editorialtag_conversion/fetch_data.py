import os
import re
import ast
import csv
import json
import shutil
import certifi
import gspread
import urllib.request
import dateutil.parser
from datetime import datetime
from oauth2client.service_account import ServiceAccountCredentials

from private.python.managers.log_manager import log_update, log_progress_bar


class Fetch_Data():
  target_site = os.environ['TARGET_SITE']
  live_site = os.environ['LIVE_SITE']
  run_time = datetime.now().strftime("%Y%m%d%H%M%S")

  ## Settings
  fetch_per_page = 25
  google_sheet_key = '1H-fF1RIX58A5V3Lx1VANWi2FwasevxTUHTM-2nILaN0'

  ## Iterators
  current_post = None
  iterations = {'posts': 1, 'tags': 1, 'categories': 1, 'dev-tags': 1}

  ## Folders
  conversion_root = './private/python/editorialtag_conversion'
  temp_path = '.temp'
  creds_path = '_creds'
  data_path = '_data'
  results_path = '_results'
  tags_file_json = 'data-tags.json'
  posts_file_json = 'data-posts.json'
  categories_file_json = 'data-categories.json'
  dev_tags_file_json = 'data-dev-tags.json'
  conversion_file_json = 'data-conversion.json'
  progress_file_json = 'data-progress.json'
  score_file_json = 'data-score.json'
  results_file = 'RESULTS.csv'


  fetch_posts_link = f'{live_site}/wp-json/wp/v2/posts'
  fetch_tags_link = f'{live_site}/wp-json/wp/v2/tags'
  fetch_categories_link = f'{target_site}/wp-json/wp/v2/categories'
  url_args = f'?per_page={fetch_per_page}&page='

  temp_dir_exists = os.path.exists(f'{conversion_root}/{temp_path}')
  data_dir_exists = os.path.exists(f'{conversion_root}/{data_path}')
  results_dir_exists = os.path.exists(f'{conversion_root}/{results_path}')


  scope = ['https://spreadsheets.google.com/feeds',
           'https://www.googleapis.com/auth/drive']

  credentials = ServiceAccountCredentials.from_json_keyfile_name(
    f'{conversion_root}/{creds_path}/20191031-creds-430e56afa71a.json', 
    scope)

  google_client = gspread.authorize(credentials)


  def __init__(self, update_data=False, date_limit=None):
    self.update_data = update_data

    self.date_limit = dateutil.parser.parse(date_limit) if date_limit else None
    self.file_paths = self.compile_paths([
      'posts',
      'tags',
      'categories',
      'conversion',
      'score',
      'results',
      'progress',
      'dev-results'])


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

    if not self.results_dir_exists:
      self.create_directory('results')


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
    elif dir == 'results':
      path = f'{self.conversion_root}/{self.results_path}'
    
    if path:
      try:
        os.mkdir(path)

        if dir == 'temp':
          self.temp_dir_exists = True
        elif dir == 'data':
          self.data_dir_exists = True
        elif dir == 'results':
          self.results_dir_exists = True

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


  def fetch_categories(self):
    '''
    This is called from check_categories_data if the file 
    'conversion-data/categories-json.txt' doesn't exist
    '''

    try:
      with urllib.request.urlopen(
        self.fetch_categories_link + 
        self.url_args + 
        str(self.iterations['categories']), cafile=certifi.where()) as res:
        data = res.read()
        total_pages = int(res.info().get('X-Wp-Totalpages'))

    except (urllib.error.HTTPError, urllib.error.URLError) as e:
      data = None

    if data:
      path = self.get_file_path('categories', True)
      file_ = open(path, 'wb')
      file_.write(data)
      file_.close()

      with open(path) as json_file:
        json_data = json.load(json_file)

    else:
      json_data = None

    if json_data:
      for n in json_data:
        self.temp_categories_data[n['id']] = {
          "name": n['name'], 
          "slug": n['slug'],
          "parent": n['parent']
        }

      log_progress_bar(
        self.iterations['categories'], 
        total_pages, 
        'Fetching categories data',
        'Categories successfully fetched')
        
      self.iterations['categories'] += 1

      self.fetch_categories()

    else:
      path = self.get_file_path('categories')
      file_ = open(path, 'w')
      file_.write(json.dumps(self.temp_categories_data))
      file_.close()


  def fetch_tags(self):
    '''
    This is called from check_tags_data if the file 
    'conversion-data/tags-json.txt' doesn't exist
    '''

    try:
      with urllib.request.urlopen(
        self.fetch_tags_link + 
        self.url_args + 
        str(self.iterations['tags']), cafile=certifi.where()) as res:
        data = res.read()
        total_pages = int(res.info().get('X-Wp-Totalpages'))

    except (urllib.error.HTTPError, urllib.error.URLError) as e:
      data = None

    if data:
      path = self.get_file_path('tags', True)
      file_ = open(path, 'wb')
      file_.write(data)
      file_.close()

      with open(path) as json_file:
        json_data = json.load(json_file)

    else:
      json_data = None

    if json_data:
      for n in json_data:
        self.temp_tag_data[n['id']] = {
          "name": n['name'], 
          "slug": n['slug']
        }

      log_progress_bar(
        self.iterations['tags'], 
        total_pages, 
        'Fetching tags data',
        'Tags successfully fetched')

      self.iterations['tags'] += 1

      self.fetch_tags()

    else:
      path = self.get_file_path('tags')
      file_ = open(path, 'w')
      file_.write(json.dumps(self.temp_tag_data))
      file_.close()


  def fetch_posts(self):
    '''
    This is called from check_posts_data if the file 
    'conversion-data/posts-json.txt' doesn't exist
    '''

    try:
      with urllib.request.urlopen(
        self.fetch_posts_link + 
        self.url_args + 
        str(self.iterations['posts']), cafile=certifi.where()) as res:
        data = res.read()
        total_pages = int(res.info().get('X-Wp-Totalpages'))

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
        if self.date_limit \
          and dateutil.parser.parse(n['date']) < self.date_limit: 
          break

        self.temp_post_data.append({
          "id": n['id'],
          "link": n['link'], 
          "tags": n['tags'],
          "category": n['categories']
        })

      log_progress_bar(
        self.iterations['posts'], 
        total_pages, 
        'Fetching post data', 
        'Posts successfully fetched')

      self.iterations['posts'] += 1

      self.fetch_posts()

    else:
      path = self.get_file_path('posts')
      file_ = open(path, 'w')
      file_.write(json.dumps(self.temp_post_data))
      file_.close()


  def check_score_data(self):
    '''
    Make sure "_data/tag-scores.json" exists and convert 
    to "data-score.json"
    '''

    score_json_file = self.get_file_path('score')

    if os.path.exists(score_json_file):
      log_update(f'Loading "{self.score_file_json}"')
    else:
      self.convert_score_csv()


  def check_conversion_data(self):
    '''
    Make sure "_data/tag-conversions.json" exists and convert 
    to "data-conversion.json"
    '''

    conversion_json_file = self.get_file_path('conversion')

    if os.path.exists(conversion_json_file):
      log_update(f'Loading "{self.conversion_file_json}"')
    else:
      self.convert_conversion_csv()


  def check_categories_data(self):
    '''
    Check if the categories data exists, if not,
    fetch the categories
    '''

    categories_file = self.get_file_path('categories')
    file_exists = False
    recompile_categories = True

    if os.path.exists(categories_file):
      file_exists = True

      with open(categories_file, 'r') as json_categories, \
        urllib.request.urlopen(self.fetch_categories_link, cafile=certifi.where()) as res:
        r = json_categories.read()
        temp_categories_data = ast.literal_eval(r)

        if int(res.info().get('X-Wp-Total')) == len(temp_categories_data):
          log_update('Categories have been compiled and are up to date')

          self.temp_categories_data = temp_categories_data
          recompile_categories = False

    if not file_exists or \
      (recompile_categories and self.update_data):
      self.temp_categories_data = {}
      self.fetch_categories()

    else:
      if file_exists:
        log_update('Loading "_data/data-categories.json"')
      else:
        log_update('Missing "_data/data-categories.json"')


  def check_tags_data(self):
    '''
    Check if the tags data exists, if not,
    fetch the tags
    '''

    tags_file = self.get_file_path('tags')
    file_exists = False
    recompile_tags = True

    if os.path.exists(tags_file):
      file_exists = True

      with open(tags_file, 'r') as json_tags, \
        urllib.request.urlopen(self.fetch_tags_link, cafile=certifi.where()) as res:
        r = json_tags.read()
        temp_tag_data = ast.literal_eval(r)

        if int(res.info().get('X-Wp-Total')) == len(temp_tag_data):
          log_update('Tags have been compiled and are up to date')

          self.temp_tag_data = temp_tag_data
          recompile_tags = False

    if not file_exists or \
      (recompile_tags and self.update_data):
      self.temp_tag_data = {}
      self.fetch_tags()

    else:
      if file_exists:
        log_update('Loading "_data/data-tags.json"')
      else:
        log_update('Missing "_data/data-tags.json"')


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
        urllib.request.urlopen(self.fetch_posts_link, cafile=certifi.where()) as res:
        r = json_posts.read()
        temp_post_data = ast.literal_eval(r)

        if int(res.info().get('X-Wp-Total')) == len(temp_post_data):
          log_update('Posts have been compiled and are up to date')

          self.temp_post_data = temp_post_data
          recompile_posts = False

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

    self.check_conversion_data()
    self.check_score_data()
    self.check_tags_data()
    self.check_categories_data()
    self.check_posts_data()



  def open_data_files(self, path):
    '''
    Open data file
    '''

    data = None

    with open(self.file_paths[path], 'r') as f:
      data = json.load(f)

    return data



  def convert_score_csv(self):
    '''
    Convert google spreadsheet to a JSON file
    '''

    log_update(f'Converting scores from Google Sheet to JSON')

    data = {}
    csv_file = self.google_client.open_by_key(self.google_sheet_key).worksheet('scores').get_all_values()
    csv_file.pop(0)
    
    for rows in csv_file:
      name = rows[0]
      data[name] = int(rows[1])
    
    with open(self.get_file_path('score'), 'w') as json_file:
      json_file.write(json.dumps(data))


  def convert_conversion_csv(self):
    '''
    Convert google spreadsheet to a JSON file
    '''

    log_update(f'Converting conversions from Google Sheet to JSON')

    data = {}
    csv_file = self.google_client.open_by_key(self.google_sheet_key).worksheet('conversions').get_all_values()
    csv_file.pop(0)
    
    for rows in csv_file:
      slug = rows[0]
      data[slug] = {
        "conversion1": rows[1],
        "conversion2": rows[2],
        "backend_tag": True if rows[3] == 'TRUE' else False,
        "franchise_tag": True if rows[4] == 'TRUE' else False
      }
    
    with open(self.get_file_path('conversion'), 'w') as json_file:
      json_file.write(json.dumps(data))


  def get_file_path(self, target, temp=False, file_name=None):
    '''
    Build file path based on class variables
    '''
    file_path = ''
    if target == 'tags':
      file_path += f'{self.conversion_root}/'
      if temp: 
        file_path += f'{self.temp_path}/{target}.txt'
      else:
        file_path += f'{self.data_path}/{self.tags_file_json}'

    if target == 'dev-tags':
      file_path += f'{self.conversion_root}/'
      if temp: 
        file_path += f'{self.temp_path}/{target}.txt'
      else:
        file_path += f'{self.data_path}/{self.dev_tags_file_json}'

    elif target == 'posts':
      file_path += f'{self.conversion_root}/'
      if temp: 
        file_path += f'{self.temp_path}/{target}.txt'
      else:
        file_path += f'{self.data_path}/{self.posts_file_json}'

    elif target == 'categories':
      file_path += f'{self.conversion_root}/{self.data_path}/{self.categories_file_json}'

    elif target == 'progress':
      file_path += f'{self.conversion_root}/{self.data_path}/{self.progress_file_json}'

    elif target == 'conversion':
      file_path += f'{self.conversion_root}/{self.data_path}/{self.conversion_file_json}'

    elif target == 'score':
      file_path += f'{self.conversion_root}/{self.data_path}/{self.score_file_json}'

    elif target == 'results':
      if file_name:
        file_path += f'{self.conversion_root}/{self.results_path}/{file_name}'
        
      else:
        file_path += f'{self.conversion_root}/{self.results_path}/{self.run_time}-{self.results_file}'

    elif target == 'dev-results':
      end = self.results_file.find('.')
      output = self.results_file[:end] \
        + '--dev' \
        + self.results_file[end:]

      file_path += f'{self.conversion_root}/{self.results_path}/{self.run_time}-{output}'

    return file_path
