Contains various scraping utilities for getting past and current champion rotation history.

The Python files call PHP scripts which do database insertions of the data.

`release_data_scraper.py` gets the release date of champions and sends data to PHP script to update database values.

`scraper.py` scans through League of Legends wiki to get every weekly rotation back through season 1, and sends data to PHP script for database insertion.
