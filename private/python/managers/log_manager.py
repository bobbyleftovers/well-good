import sys


def log_update(message=''):
  '''
  Add note for each process that has run
  '''

  new_line = "\n"
  print(f'{message}{new_line}')


def log_progress_bar(count, total, process_msg='', success_msg=''):
  '''
  Display a progress bar in command line
  for a process that is running
  '''

  rewrite = "\r"
  new_line = "\n"
  bar_len = 30
  filled_len = int(round(bar_len * count / float(total)))

  percents = round(100.0 * count / float(total), 1)
  bar = '=' * filled_len + '-' * (bar_len - filled_len)

  sys.stdout.write(f'[{bar}] {process_msg} | {percents}%{rewrite}')
  sys.stdout.flush()
  
  if count == total:
    print(new_line)
    print(f'{success_msg}{new_line}')
