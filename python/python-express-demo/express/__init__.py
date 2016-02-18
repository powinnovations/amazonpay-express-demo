import hmac
import html
import time
import json
import random
import base64
import hashlib
import datetime
from urllib import parse
from collections import OrderedDict
from flask import Flask, session, render_template, url_for, redirect, request
from express.config import pay_config

app = Flask(__name__)
app.secret_key = 'my_super_secret_key!'

import ssl
context = ssl.SSLContext(ssl.PROTOCOL_TLSv1_2)
context.load_cert_chain('/etc/apache2/ssl/ssl.crt', '/etc/apache2/ssl/private.key')


@app.route('/')
def index():
    session['return_url'] = pay_config['return_url']
    session['cancel_return_url'] = pay_config['cancel_return_url']
    session['client_id'] = pay_config['client_id']
    session['client_secret'] = pay_config['client_secret']
    session['mws_access_key'] = pay_config['mws_access_key']
    session['mws_secret_key'] = pay_config['mws_secret_key']
    session['merchant_id'] = pay_config['merchant_id']
    return render_template('express.html')

@app.route('/response', methods=['GET'])
def response():
    return render_template('response.html',
        result_code=request.args.get('resultCode'),
        oro=request.args.get('orderReferenceId'),
        seller_order_id=request.args.get('sellerOrderId'))

@app.route('/cancel', methods=['GET'])
def response():
    return render_template('cancel.html',
        result_code=request.args.get('resultCode')

@app.route('/express_signature', methods=['GET'])
def signature():
    amount = request.args.get('amount')
    currency_code = request.args.get('currencyCode')
    seller_note = request.args.get('sellerNote')
    seller_order_id = request.args.get('sellerOrderId')

    parameters = {
        'accessKey': session['mws_access_key'],
        'amount': amount,
        'sellerId': session['merchant_id'],
        'returnURL': session['return_url'],
        'cancelReturnURL': session['cancel_return_url'],
        'lwaClientId': session['client_id'],
        'sellerNote': seller_note,
        'sellerOrderId': seller_order_id,
        'currencyCode': currency_code,
        'shippingAddressRequired': 'true',
        'paymentAction': 'AuthorizeAndCapture'}

    # create querystring to sign
    string_to_sign = 'POST\npayments.amazon.com\n/\n{}'.format(
        parse.urlencode(sorted(parameters.items())).replace('+', '%20').replace('*', '%2A').replace('%7E', '~'))

    # generate signature
    signature = hmac.new(
        session['mws_secret_key'].encode('utf_8'),
        msg=string_to_sign.encode('utf_8'),
        digestmod=hashlib.sha256).digest()
    signature = base64.b64encode(signature).decode()

    # add signature to parameter list
    parameters['signature'] = parse.quote_plus(signature)

    # order the parameters and move signature to the end
    ordered_parameters = OrderedDict(sorted(parameters.items()))
    ordered_parameters.move_to_end('signature')

    # return it
    #return json.dumps(parse.urlencode(ordered_parameters).encode(encoding='utf_8'))
    return json.dumps(ordered_parameters)


def rand():
    return random.randint(
        0, 9999) + random.randint(0, 9999) + random.randint(0, 9999)

if __name__ == '__main__':
    app.run()
