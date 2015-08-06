#!/usr/bin/python3

import sys
import logging
logging.basicConfig(stream=sys.stderr)
sys.path.insert(0, '/var/www/wsgi/python-express-demo/')

from express import app as application

