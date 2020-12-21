'''
To run from command line:

## Set file path to local theme path
## (example)
export PYTHONPATH=/Users/gradywoodruff/Sites/well-good/

## Run python
python

## Start function
file = '/Users/gradywoodruff/Dropbox/_clients/barrel/projects/01-wellandgood/esp-conversion/member_export-5.csv'
env = 'production'
convert = Convert_Subscribers(file, env)
convert.convert_subscribers()
'''


import os
import ast
import csv
import json
import math
import html
import requests
from datetime import datetime

from private.python.esp_conversion.fetch_data import Fetch_Data
from private.python.managers.log_manager import log_update, log_progress_bar


class Convert_Subscribers():
  ## Settings
  api_limit = 1000

  ## Data
  list_subscribers = None


  def __init__(self, csv_file='', env='sandbox'):
    self.data = Fetch_Data(csv_file)

    if env == 'production':
      self.api_key = os.environ['ITERABLE_KEY']
    else:
      self.api_key = os.environ['ITERABLE_SANDBOX_KEY']

    self.api_url = 'https://api.iterable.com/api/users/bulkUpdate'
    self.headers = {
      'Content-Type': 'application/json',
      'Api-Key': self.api_key}
    self.file_paths = self.data.compile_paths([
      'subscribers'])


  def open_data_files(self, path):
    '''
    Open data file
    '''

    data = None

    with open(self.file_paths[path], 'r') as f:
      data = json.load(f)

    return data


  def convert_subscribers(self):
    '''
    '''

    ## Check directories and data
    self.data.check_directories()
    self.data.check_subscriber_data()

    ## Open data files
    self.list_subscribers = self.open_data_files('subscribers')

    total_subscribers = len(self.list_subscribers)
    api_calls = math.ceil(total_subscribers / self.api_limit)
    per = math.ceil(total_subscribers / api_calls)
    for i in range(0, api_calls):
      start = per * i
      end = start + per
      
      data = json.dumps({'users': self.list_subscribers[start:end]})
      response = requests.post(
        self.api_url,
        headers=self.headers,
        data=data)

      log_progress_bar(
        i+1, 
        api_calls, 
        'Sending data to Iterable', 
        'Subscriber conversion complete')
      
    ## Delete temporary directory
    if self.data.temp_dir_exists:
      self.data.remove_directory('temp')
