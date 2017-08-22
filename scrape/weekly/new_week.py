#!/usr/bin/python

import json
import requests
from bs4 import BeautifulSoup

execfile('../keys.py')

# Weekly rotation announcement site. The week's free rotation data is scraped
# from here.
url = 'http://na.leagueoflegends.com/en/news/champions-skins/free-rotation'

# Static champion data. Used to get the ID of the champion to insert in the database.
champion_data_url = 'https://ddragon.leagueoflegends.com/cdn/6.24.1/data/en_US/champion.json'

# URL to send data to, which handles insertion into database.
send_url = 'http://localhost/scrape/weekly/new_week.php'

data = { 'key': week_key, 'champions': [] } # make sure key matches in file

# Get static data and set as property on data object.
raw_data = requests.get(champion_data_url).json()
data['champion_data'] = raw_data['data']

soup = BeautifulSoup(requests.get(url).text, 'html.parser')

recent = soup.findAll('div', { 'class': 'default-2-3' })[0]
a = recent.find('a', href=True)
link = 'http://na.leagueoflegends.com' + a['href']

soup = BeautifulSoup(requests.get(link).text, 'html.parser')

cards = soup.findAll('div', { 'class': 'f2p-card' })

for card in cards:
    span = card.find('span', { 'class': 'champion-name' }).findAll(text=True)
    champion = span[0].strip() # deals with case where champion name is not a link
    if len(span) > 1:
        champion = span[1]
    data['champions'].append(champion)

data_json = json.dumps(data)
payload = { 'data': data_json }
r = requests.post(send_url, data=payload)
print(r.text)  # print response from server
