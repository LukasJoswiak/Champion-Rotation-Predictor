# use new_week.py

import json
import requests
from bs4 import BeautifulSoup

execfile('../keys.py')

url = 'http://localhost/scrape/weekly/week.php'

data = { 'key': week_key } # make sure key matches in file

data_json = json.dumps(data)
payload = { 'data': data_json }
r = requests.post(url, data=payload)
print(r.text)
