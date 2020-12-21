import os
import csv
import json
import shutil
import dateutil.parser as parser

from datetime import datetime, timezone

from private.python.esp_conversion.models.subscriber import Subscriber
from private.python.managers.log_manager import log_update, log_progress_bar


class Fetch_Data():
  run_time = datetime.now().strftime("%Y%m%d%H%M%S")

  ## Folders
  conversion_root = './private/python/esp_conversion'
  temp_path = '.temp'
  data_path = '_data'
  results_path = '_results'
  subscribers_file_json = 'data-subscribers.json'
  score_file_json = 'data-score.json'
  results_file = 'RESULTS.csv'


  def __init__(self, csv_file=''):
    self.csv = csv_file

    self.temp_dir_exists = os.path.exists(self.get_file_path('temp'))
    self.data_dir_exists = os.path.exists(self.get_file_path('data'))
    self.results_dir_exists = os.path.exists(self.get_file_path('results'))


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

    path = self.get_file_path(dir)
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
      path = self.get_file_path('temp')
    

    if path and os.path.exists(path):
      try:
        shutil.rmtree(path)
        
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


  def get_file_path(self, target):
    '''
    Build file path based on class variables
    '''

    path = ''
    if target == 'data':
      path += f'{self.conversion_root}/{self.data_path}'

    elif target == 'results':
      path += f'{self.conversion_root}/{self.results_path}'

    elif target == 'temp':
      path += f'{self.conversion_root}/{self.temp_path}'

    elif target == 'subscribers':
      path += f'{self.conversion_root}/{self.data_path}/{self.subscribers_file_json}'

    return path


  def check_subscriber_data(self):
    '''
    Make sure "_data/data-subscribers.csv" exists and convert 
    to "data-score.json"
    '''

    if os.path.exists(self.csv):
      self.convert_subscriber_csv()
    else:
      log_update(f'Missing CSV file')
      return None


  def convert_subscriber_csv(self):
    '''
    Convert input to a JSON file
    '''
    
    log_update(f'Converting CSV to JSON')

    data = {}
    with open(self.csv) as csv_file:
      csv_reader = csv.DictReader(csv_file)
      for row in csv_reader:
        subscriber = Subscriber(row)
        if not subscriber.is_valid: continue
          
        if subscriber.email in data:
          priors = data[subscriber.email]
        else:
          priors = None

        data[subscriber.email] = subscriber.compile_data(priors)
    
    data = [v for v in data.values()]
    with open(self.get_file_path('subscribers'), 'w') as json_file:
      json_file.write(json.dumps(data))
