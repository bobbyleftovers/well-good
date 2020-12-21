import re
import dateutil.parser as parser

from datetime import datetime, timezone

from api.managers.state_abbreviation import state_abbreviation
from api.managers.format_time import format_time


class Subscriber():
  banned_emails = [
    'postuptester@gmail.com']

  
  def __init__(self, row):
    self.email = ''
    self.phone = ''
    self.devices = []
    self.ip = ''
    self.locale = ''
    self.time_zone = ''
    self.referred_by = ''
    self.favorite_categories = []
    self.num_bounces = ''
    self.signup_method = ''
    self.signup_ip = ''
    self.source_signup_date = ''
    self.source_desc = ''
    self.date_last_clicked = ''
    self.date_last_opened = ''
    self.first_name = ''
    self.last_name = ''
    self.address_1 = ''
    self.address_2 = ''
    self.city = ''
    self.state = ''
    self.zip_code = ''
    self.country = ''
    self.gender = ''
    self.date_of_birth = ''
    self.home_phone = ''
    self.work_phone = ''
    self.country_code = ''
    self.name = ''
    self.timezone = ''
    self.dojomojo_campaign = ''
    self.capture_widget = ''
    self.sweepstakes_id = ''
    self.sweepstakes_entry_date = ''
    self.sweepstakes_ip = ''
    self.confirmed = ''
    self.date_joined = ''
    self.date_unsub = ''
    self.source_id = ''
    self.status = ''

    self.is_valid = False
    self.process_row(row)


  def validate_email(self):
    '''
    '''
    
    regex = '^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$'
    is_valid = True

    if self.email in self.banned_emails:
      is_valid = False

    if not re.search(regex, self.email): 
      is_valid = False

    return is_valid


  def process_row(self, row):
    '''
    '''

    ## General Data
    self.email = row['Recips.Address'] \
      if row.get('Recips.Address') != None else ''

    ## Return if email is not valid
    self.is_valid = self.validate_email()
    if not self.is_valid: return
  
    self.phone = row['RecipsAdditional._HomePhone'] \
      or row['RecipsAdditional._WorkPhone'] if \
      row['RecipsAdditional._HomePhone'] \
      or row['RecipsAdditional._WorkPhone'] else ''
    
    self.devices = []
    
    self.ip = row['Recips.SignupIP'] if row['Recips.SignupIP'] else ''
    
    self.locale = ''
    
    self.time_zone = row['TimeZone']
    
    self.referred_by = row['Referred_by']
    
    self.favorite_categories = []
    
    self.first_name = row['RecipsAdditional.FirstName'].capitalize() \
      if row.get('RecipsAdditional.FirstName') != None else ''
    
    self.last_name = row['RecipsAdditional.LastName'].capitalize() \
      if row.get('RecipsAdditional.LastName') != None else ''
    
    self.date_joined = format_time(row['Recips.DateJoined'], '%Y-%m-%d %H:%M:%S.%f') \
      if row.get('Recips.DateJoined') else ''
    
    self.country = row['RecipsAdditional._Country'] \
      if row.get('RecipsAdditional._Country') != None else ''
    is_american = self.country.lower() in [
      'united states',
      'us',
      'usa',
      'united states of america']
    
    self.city = ' '.join(w.capitalize() for w in row['RecipsAdditional._City'].split()) \
      if row.get('RecipsAdditional._City') != None else ''

    if is_american:
      self.state = state_abbreviation(row['RecipsAdditional._State']) \
        if row.get('RecipsAdditional._State') != None else ''
    else:
      self.state = row['RecipsAdditional._State'] \
        if row.get('RecipsAdditional._State') != None else ''

    try:
      zip_code = int(row['RecipsAdditional._PostalCode'].strip()) \
        if row.get('RecipsAdditional._PostalCode') != None else ''
      if (is_american and len(str(zip_code)) == 5) or not is_american:
        self.zip_code = zip_code
      else:
        self.zip_code = None
    except ValueError:
      self.zip_code = None

    self.status = row['Recips.Status'] \
      if row.get('Recips.Status') != None else ''
    
    self.num_bounces = int(row['Recips.NumBounces']) \
      if row.get('Recips.NumBounces') else 0
    
    self.date_unsub = format_time(row['Recips.DateUnsub'], '%Y-%m-%d %H:%M:%S.%f') \
      if row.get('Recips.DateUnsub') else ''
    
    self.signup_method = row['Recips.SignupMethod'] \
      if row.get('Recips.SignupMethod') != None else ''
    
    self.signup_ip = row['Recips.SignupIP'] \
      if row.get('Recips.SignupIP') != None else ''
    
    self.source_signup_date = format_time(row['Recips.SourceSignupDate'], '%Y/%m/%d %H:%M') \
      if row.get('Recips.SourceSignupDate') else ''
    
    self.source_desc = row['Recips.SourceDesc'] \
      if row.get('Recips.SourceDesc') != None else ''
    
    self.date_last_clicked = format_time(row['Recips.DateLastClicked'], '%Y-%m-%d %H:%M:%S.%f') \
      if row.get('Recips.DateLastClicked') else ''
    
    self.date_last_opened = format_time(row['Recips.DateLastOpened'], '%Y-%m-%d %H:%M:%S.%f') \
      if row.get('Recips.DateLastOpened') else ''
    
    self.address_1 = row['RecipsAdditional._Address1'] \
      if row.get('RecipsAdditional._Address1') != None else ''
    
    self.address_2 = row['RecipsAdditional._Address2'] \
      if row.get('RecipsAdditional._Address2') != None else ''
    
    self.gender = row['RecipsAdditional._Gender'] \
      if row.get('RecipsAdditional._Gender') != None else ''
    
    self.date_of_birth = row['RecipsAdditional._DateOfBirth'] \
      if row.get('RecipsAdditional._DateOfBirth') != None else ''
    
    self.home_phone = row['RecipsAdditional._HomePhone'] \
      if row.get('RecipsAdditional._HomePhone') != None else ''
    
    self.work_phone = row['RecipsAdditional._WorkPhone'] \
      if row.get('RecipsAdditional._WorkPhone') != None else ''
    
    self.country_code = row['RecipsAdditional._CountryCode'] \
      if row.get('RecipsAdditional._CountryCode') != None else ''
    
    self.referred_by = row['Referred_by'] \
      if row.get('Referred_by') != None else ''
    
    self.name = row['Name'] \
      if row.get('Name') != None else ''
    
    self.timezone = row['TimeZone'] \
      if row.get('TimeZone') != None else ''
    
    self.dojomojo_campaign = row['DojoMojo_Campaign'] \
      if row.get('DojoMojo_Campaign') != None else ''
    
    self.capture_widget = row['CaptureWidgetName'] \
      if row.get('CaptureWidgetName') != None else ''
    
    self.sweepstakes_id = row['Sweepstakes_ID'] \
      if row.get('Sweepstakes_ID') != None else ''
    
    self.sweepstakes_entry_date = row['Sweepstakes_Entry_Date'] \
      if row.get('Sweepstakes_Entry_Date') != None else ''
    
    self.sweepstakes_ip = row['Sweepstakes_IP'] \
      if row.get('Sweepstakes_IP') != None else ''
    
    if row.get('ListRecips.Confirmed') != None:
      if row['ListRecips.Confirmed'] == '0':
        self.confirmed = False
      else:
        self.confirmed = True
    else:
      self.confirmed = False
    
    self.date_joined_list = format_time(row['ListRecips.DateJoined'], '%Y-%m-%d') \
      if row.get('ListRecips.DateJoined') else ''
    
    self.date_unsub_list = format_time(row['ListRecips.DateUnsub'], '%Y-%m-%d %H:%M:%S.%f') \
      if row.get('ListRecips.DateUnsub') else ''
    
    self.source_id = row['ListRecips.SourceID'] \
      if row.get('ListRecips.SourceID') != None else ''
    
    self.status = row['ListRecips.Status'] \
      if row.get('ListRecips.Status') != None else ''


  def get_value(self, target, key, priors=None):
    '''
    Decide whether to use the new value or the 
    value compiled on init
    '''

    if getattr(self, target):
      value = getattr(self, target)
      if priors and not value:
        value = getattr(priors, key)
    else:
      value = None

    return value


  def compile_data(self, priors=None):
    '''
    If data already exists, check the prior data
    and add new data if it exists
    '''

    user = {}

    ## General fields
    general_fields = {}
    general_priors = priors \
      if priors else None

    general_fields['email'] = self.get_value(
      'email', 'email', general_priors)
    general_fields['phoneNumber'] = self.get_value(
      'phone', 'phoneNumber', general_priors)
    general_fields['devices'] = self.get_value(
      'devices', 'devices', general_priors)
    general_fields['ip'] = self.get_value(
      'ip', 'ip', general_priors)
    general_fields['locale'] = self.get_value(
      'locale', 'locale', general_priors)
    general_fields['timeZone'] = self.get_value(
      'time_zone', 'timeZone', general_priors)
    general_fields['referredByEmail'] = self.get_value(
      'referred_by', 'referredByEmail', general_priors)
    general_fields['favoriteCategories'] = self.get_value(
      'favorite_categories', 'favoriteCategories', general_priors)

    ## Data fields
    data_fields = {}
    data_priors = priors['dataFields'] \
      if priors and priors.get('dataFields') \
      else None

    data_fields['firstName'] = self.get_value(
      'first_name', 'firstName', data_priors)
    data_fields['lastName'] = self.get_value(
      'last_name', 'lastName', data_priors)
    data_fields['city'] = self.get_value(
      'city', 'city', data_priors)
    data_fields['state'] = self.get_value(
      'state', 'state', data_priors)
    data_fields['zipCode'] = self.get_value(
      'zip_code', 'zipCode', data_priors)

    ## Postup legacy fields
    postup_fields = {}
    postup_priors = priors['dataFields']['postupLegacy'] \
      if priors and priors.get('dataFields') and priors['dataFields'].get('postupLegacy') \
      else None

    postup_fields['Recips_Address'] = self.get_value(
      'email', 'Recips_Address', postup_priors)
    postup_fields['Recips_Status'] = self.get_value(
      'status', 'Recips_Status', postup_priors)
    postup_fields['Recips_NumBounces'] = self.get_value(
      'num_bounces', 'Recips_NumBounces', postup_priors)
    postup_fields['Recips_DateUnsub'] = self.get_value(
      'date_unsub', 'Recips_DateUnsub', postup_priors)
    postup_fields['Recips_DateJoined'] = self.get_value(
      'date_joined', 'Recips_DateJoined', postup_priors)
    postup_fields['Recips_SignupMethod'] = self.get_value(
      'signup_method', 'Recips_SignupMethod', postup_priors)
    postup_fields['Recips_SignupIP'] = self.get_value(
      'signup_ip', 'Recips_SignupIP', postup_priors)
    postup_fields['Recips_SourceSignupDate'] = self.get_value(
      'source_signup_date', 'Recips_SourceSignupDate', postup_priors)
    postup_fields['Recips_SourceDesc'] = self.get_value(
      'source_desc', 'Recips_SourceDesc', postup_priors)
    postup_fields['Recips_DateLastClicked'] = self.get_value(
      'date_last_clicked', 'Recips_DateLastClicked', postup_priors)
    postup_fields['Recips_DateLastOpened'] = self.get_value(
      'date_last_opened', 'Recips_DateLastOpened', postup_priors)
    postup_fields['RecipsAdditional_FirstName'] = self.get_value(
      'first_name', 'RecipsAdditional_FirstName', postup_priors)
    postup_fields['RecipsAdditional_LastName'] = self.get_value(
      'last_name', 'RecipsAdditional_LastName', postup_priors)
    postup_fields['RecipsAdditional__Address1'] = self.get_value(
      'address_1', 'RecipsAdditional__Address1', postup_priors)
    postup_fields['RecipsAdditional__Address2'] = self.get_value(
      'address_2', 'RecipsAdditional__Address2', postup_priors)
    postup_fields['RecipsAdditional__City'] = self.get_value(
      'city', 'RecipsAdditional__City', postup_priors)
    postup_fields['RecipsAdditional__State'] = self.get_value(
      'state', 'RecipsAdditional__State', postup_priors)
    postup_fields['RecipsAdditional__PostalCode'] = self.get_value(
      'zip_code', 'RecipsAdditional__PostalCode', postup_priors)
    postup_fields['RecipsAdditional__Country'] = self.get_value(
      'country', 'RecipsAdditional__Country', postup_priors)
    postup_fields['RecipsAdditional__Gender'] = self.get_value(
      'gender', 'RecipsAdditional__Gender', postup_priors)
    postup_fields['RecipsAdditional__DateOfBirth'] = self.get_value(
      'date_of_birth', 'RecipsAdditional__DateOfBirth', postup_priors)
    postup_fields['RecipsAdditional__HomePhone'] = self.get_value(
      'home_phone', 'RecipsAdditional__WorkPhone', postup_priors)
    postup_fields['RecipsAdditional__HomePhone'] = self.get_value(
      'work_phone', 'RecipsAdditional__WorkPhone', postup_priors)
    postup_fields['RecipsAdditional__CountryCode'] = self.get_value(
      'country_code', 'RecipsAdditional__CountryCode', postup_priors)
    postup_fields['Referred_by'] = self.get_value(
      'referred_by', 'Referred_by', postup_priors)
    postup_fields['Name'] = self.get_value(
      'name', 'Name', postup_priors)
    postup_fields['TimeZone'] = self.get_value(
      'timezone', 'TimeZone', postup_priors)
    postup_fields['DojoMojo_Campaign'] = self.get_value(
      'dojomojo_campaign', 'DojoMojo_Campaign', postup_priors)
    postup_fields['CaptureWidgetName'] = self.get_value(
      'capture_widget', 'CaptureWidgetName', postup_priors)
    postup_fields['Sweepstakes_ID'] = self.get_value(
      'sweepstakes_id', 'Sweepstakes_ID', postup_priors)
    postup_fields['Sweepstakes_Entry_Date'] = self.get_value(
      'sweepstakes_entry_date', 'Sweepstakes_Entry_Date', postup_priors)
    postup_fields['Sweepstakes_IP'] = self.get_value(
      'sweepstakes_ip', 'Sweepstakes_IP', postup_priors)
    postup_fields['ListRecips_Confirmed'] = self.get_value(
      'confirmed', 'ListRecips_Confirmed', postup_priors)
    postup_fields['ListRecips_DateJoined'] = self.get_value(
      'date_joined_list', 'ListRecips_DateJoined', postup_priors)
    postup_fields['ListRecips_DateUnsub'] = self.get_value(
      'date_unsub_list', 'ListRecips_DateUnsub', postup_priors)
    postup_fields['ListRecips_SourceID'] = self.get_value(
      'source_id', 'ListRecips_SourceID', postup_priors)
    postup_fields['ListRecips_Status'] = self.get_value(
      'status', 'ListRecips_Status', postup_priors)

    ## Options
    user = {k: v for k, v in general_fields.items() if v != None and v != ''}
    user['dataFields'] = {k: v for k, v in data_fields.items() if v != None and v != ''}
    user['dataFields']['postupLegacy'] = {k: v for k, v in postup_fields.items() if v != None and v != ''}
    user['mergeNestedObjects'] = True
    user['preferUserId'] = True

    return user
