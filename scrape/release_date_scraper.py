import os
import json
import requests
from bs4 import BeautifulSoup

# import secret keys
execfile('./keys.py')

url = 'http://leagueoflegends.wikia.com/wiki/List_of_champions'

soup = BeautifulSoup(requests.get(url).text, 'html.parser')

trs = soup.find('table', { 'class': 'stdt' }).findAll('tr')

data = {}

index = 0
for tr in trs:
	td = tr.findAll('td')
	champion = td[0::1]
	date = td[7::8]

	if len(champion) > 0:
		champion = champion[0].findAll(text=True)[1]
		date = date[0].findAll(text=True)[0].rstrip()
		print(champion + " released on " + date)
		data[index] = { "champion": champion, "date": date }

		index += 1

# print(json.dumps(data, indent = 4))

data_json = json.dumps(data)
payload = { 'data': data_json, 'key': update_key }
r = requests.post('http://localhost/scrape/update_champion.php', data=payload)
print(r.text)
