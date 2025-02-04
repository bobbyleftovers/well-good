
def state_abbreviation(name):
  '''
  Get abbreviation from state if an
  abbreviation exists
  '''
  abbrevs = {
    'alabama': 'al',
    'alaska': 'ak',
    'arizona': 'az',
    'arkansas': 'ar',
    'california': 'ca',
    'colorado': 'co',
    'connecticut': 'ct',
    'delaware': 'de',
    'district of columbia': 'dc',
    'florida': 'fl',
    'georgia': 'ga',
    'hawaii': 'hi',
    'idaho': 'id',
    'illinois': 'il',
    'indiana': 'in',
    'iowa': 'ia',
    'kansas': 'ks',
    'kentucky': 'ky',
    'louisiana': 'la',
    'maine': 'me',
    'maryland': 'md',
    'massachusetts': 'ma',
    'michigan': 'mi',
    'minnesota': 'mn',
    'mississippi': 'ms',
    'missouri': 'mo',
    'montana': 'mt',
    'nebraska': 'ne',
    'nevada': 'nv',
    'new hampshire': 'nh',
    'new jersey': 'nj',
    'new mexico': 'nm',
    'new york': 'ny',
    'north carolina': 'nc',
    'north dakota': 'nd',
    'northern mariana islands': 'mp',
    'ohio': 'oh',
    'oklahoma': 'ok',
    'oregon': 'or',
    'palau': 'pw',
    'pennsylvania': 'pa',
    'puerto Rico': 'pr',
    'rhode island': 'ri',
    'south carolina': 'sc',
    'south dakota': 'sd',
    'tennessee': 'tn',
    'texas': 'tx',
    'utah': 'ut',
    'vermont': 'vt',
    'virgin islands': 'vi',
    'virginia': 'va',
    'washington': 'wa',
    'west virginia': 'wv',
    'wisconsin': 'wi',
    'wyoming': 'wy',
  }
  for state, abbrev in abbrevs.items():
    if name.lower() == state \
      or name.lower() == abbrev:
      return abbrev.upper()

  return name
