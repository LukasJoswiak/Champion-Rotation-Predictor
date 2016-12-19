Run new_week.py once a week to scrape the latest champion rotation from Riot.

new_week.py scrapes the weeks rotation and sends the data to a PHP script which
inserts the data into a database.

week.py and week.php are old versions. Use new_week.py and new_week.php for best results.

My server has a cron job set to run on Mondays around noon which executes new_week.py,
automatically inserting the weeks rotation into my database.
