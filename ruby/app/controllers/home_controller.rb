class HomeController < ApplicationController
  def index
  end

  # = Pay with Amazon Express Payments Demo
  # The signature method sets the required and optional parameter values and
  # initializes the Express Amazon Payments client. The method is
  # rendered in json format. A view is not needed for this method.
  def signature

    require 'express_amazon_payments.rb'

    # Mandatory fields
    # fields are defined in config/secrets.yml
    access_key = Rails.application.secrets.access_key
    secret_key = Rails.application.secrets.secret_key
    seller_id = Rails.application.secrets.seller_id
    amount = params[:amount].to_s
    return_url = Rails.application.secrets.return_url
    lwa_client_id = Rails.application.secrets.lwa_client_id

    # Optional fields
    # fields are defined in config/secrets.yml
    currency_code = params[:currency_code].to_s
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

    render :json => client.signature

  end

end
