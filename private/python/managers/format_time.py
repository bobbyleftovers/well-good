import pendulum
from datetime import datetime

def format_time(str_time, str_format):
  try:
    dt = datetime.strptime(str_time.strip(), str_format)
    split = ['%Y','%m','%d', '%H', '%M', '%S']
    dt_vals = tuple(map(lambda formatter: int(datetime.strftime(dt, formatter)), split))

    dt_tz = pendulum.datetime(*dt_vals)
    formatted = dt_tz.format('YYYY-MM-DD HH:mm:ss Z')

  except ValueError:
    formatted = None

  return formatted
