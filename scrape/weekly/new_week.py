#!/usr/bin/python

import json
import requests
from bs4 import BeautifulSoup

execfile('../keys.py')

url = 'http://na.leagueoflegends.com/en/news/champions-skins/free-rotation'
send_url = 'http://localhost/scrape/weekly/new_week.php'

data = { 'key': week_key, 'champions': [] } # make sure key matches in file

soup = BeautifulSoup(requests.get(url).text, 'html.parser')

recent = soup.findAll('div', { 'class': 'default-2-3' })[0]
a = recent.find('a', href=True)
link = 'http://na.leagueoflegends.com' + a['href']

soup = BeautifulSoup(requests.get(link).text, 'html.parser')

cards = soup.findAll('div', { 'class': 'f2p-card' })

for card in cards:
    champion = card.find('span', { 'class': 'champion-name' }).findAll(text=True)[1]
    data['champions'].append(champion)


data_json = json.dumps(data)
payload = { 'data': data_json }
r = requests.post(send_url, data=payload)
print(r.text)
