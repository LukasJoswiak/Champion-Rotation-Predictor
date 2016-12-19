# This file scrapes the entire champion rotation history from the League of Legends wiki,
# and sends the data in a POST request to a PHP script to handle the data.

import json
import requests
from bs4 import BeautifulSoup

# get secret key
execfile('keys.py')

urls = ['http://leagueoflegends.wikia.com/wiki/Champion_Rotation_Schedule_(Pre-Season_One)', 'http://leagueoflegends.wikia.com/wiki/Champion_Rotation_Schedule_(Season_One)', 'http://leagueoflegends.wikia.com/wiki/Champion_Rotation_Schedule_(Pre-Season_Two)', 'http://leagueoflegends.wikia.com/wiki/Champion_Rotation_Schedule_(Season_Two)', 'http://leagueoflegends.wikia.com/wiki/Champion_Rotation_Schedule_(Pre-Season_Three)', 'http://leagueoflegends.wikia.com/wiki/Champion_Rotation_Schedule_(Season_Three)', 'http://leagueoflegends.wikia.com/wiki/Champion_Rotation_Schedule_(Pre-Season_Four)', 'http://leagueoflegends.wikia.com/wiki/Champion_Rotation_Schedule_(Season_Four)', 'http://leagueoflegends.wikia.com/wiki/Champion_Rotation_Schedule_(Pre-Season_Five)', 'http://leagueoflegends.wikia.com/wiki/Champion_Rotation_Schedule_(Season_Five)']

for url in urls:
	soup = BeautifulSoup(requests.get(url).text, 'html.parser')

	anchors = soup.find(id='mw-content-text').findAll('table')[3].find('td').findAll('a')
	anchor = anchors[len(anchors) - 1]
	champion = anchor.findAll(text=True)[0]

	tables = soup.find(id='mw-content-text').findChildren('table', recursive=False) # find all direct table children

	data = {}

	tableNumber = 0

	for index, row in enumerate(tables):
		tables = row.findAll('table')

		for j, table in enumerate(tables):
			if j % 2 == 0:
				data[tableNumber] = {}
				data[tableNumber]['champions'] = []

				# get date
				text = table.findAll(text=True)
				year = text[4]

				if ',' not in year:
					year = ',' + year # add comma if it isn't present

				date = text[2] + year

				split = date.split(',')
				date = split[0] + ',' + split[1][0:5] # cut off any extra bits after year (this will work for 4 digit years)

				data[tableNumber]['date'] = date
			else:
				# get champions
				tds = table.findAll('td')

				for k, content in enumerate(tds):
					if k % 2 == 0:
						# get champion name
						anchors = content.findAll('a')
						anchor = anchors[len(anchors) - 1]
						text = anchor.findAll(text=True)

						if len(text) == 0:
							champion = anchor['title']
						else:
							champion = text[0]

						data[tableNumber]['champions'].append(champion)

				tableNumber += 1
						

        # print out data that script returns
	# print(json.dumps(data, indent = 4))

        # convert data to json and send to server
	data_json = json.dumps(data)
        payload = { 'data': data_json, 'key': insert_key }
        print("sending post")
	r = requests.post('http://localhost/scrape/insert.php', data=payload)
	print(r.text)
