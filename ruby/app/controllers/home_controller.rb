class HomeController < ApplicationController
  def index
  end

  def signature

    # = Pay with Amazon Express Payments Demo
    # * Copyright 2015 Amazon.com, Inc. or its affiliates. All Rights Reserved.
    # * Licensed under the Apache License, Version 2.0 (the "License");

    # You may not use this file except in compliance with the License. You may obtain
    # a copy of the License at:
    # * @see http://aws.amazon.com/apache2.0/
    # or in the "license" file accompanying this file. This file is distributed on an
    # "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or
    # implied. See the License for the specific language governing permissions and
    # limitations under the License.

    require 'express_amazon_payments.rb'

    # Mandatory fields
    # fields are defined in config/secrets.yml

    access_key = Rails.application.secrets.access_key
    secret_key = Rails.application.secrets.secret_key
    seller_id = Rails.application.secrets.seller_id
    amount = params[:payment_amount].to_s
    return_url = Rails.application.secrets.return_url
    lwa_client_id = Rails.application.secrets.lwa_client_id

    # Optional fields
    # fields are defined in config/secrets.yml

    currency_code = params[:payment_currency].to_s
    seller_note = params[:seller_note].to_s
    seller_order_id = Rails.application.secrets.seller_order_id
    shipping_address_required = Rails.application.secrets.shipping_address_required.to_s
    payment_action = Rails.application.secrets.payment_action

    # Initialize Express Amazon Payments client

    client = ExpressAmazonPayments::Client.new(
    access_key, secret_key, seller_id, amount, return_url, lwa_client_id,
    currency_code: currency_code, seller_note: seller_note, seller_order_id: seller_order_id,
    shipping_address_required: shipping_address_required, payment_action: payment_action
    )

    # The output will be in json format. The page will render json. No view is required for this method.

    render :json => client.signature

  end

end
