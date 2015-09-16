require 'cgi'
require 'base64'
require 'openssl'

module ExpressAmazonPayments

  # Pay with Amazon - Express Payments - Demo
  # This client allows a seller to dynamically integrate with Express Payments by passing in the
  # required keys and adjustable parameter values (amount, seller_note, seller_order_id, shipping_address_required).
  # The client then generates a signature that is used to make the Express Payment request.
  class Client

    # Seller keys will be available at:
    # * @see https://sellercentral.amazon.com
    # * @param access_key [string]
    # * @param secret_key [string]
    # * @param seller_id [string]
    # * @param amount [string]
    # * @param return_url [string]
    # * @param lwa_client_id [string]
    # * @optional currency_code [string] default: USD
    # * @optional payment_action [string] default: Authorize
    # * @optional seller_note [string] default:
    # * @optional seller_order_id [string] default:
    # * @optional shipping_address_required [string] default: true
    def initialize(access_key, secret_key, seller_id, amount, return_url, lwa_client_id, currency_code: 'USD', payment_action: 'Authorize', seller_note: ' ', seller_order_id: ' ', shipping_address_required: 'true')
      @access_key = access_key
      @secret_key = secret_key
      @seller_id = seller_id
      @amount = amount
      @return_url = return_url
      @lwa_client_id = lwa_client_id
      @currency_code = currency_code
      @payment_action = payment_action
      @seller_note = seller_note
      @seller_order_id = seller_order_id
      @shipping_address_required = shipping_address_required
    end

    # The signature method creates the hash with all parameter values.
    # The hash is used to URL-encode the values and generate the signatre.
    # A json formated string is returned.
    def signature

      # Build hash to generate signature
      parameters = {
        :accessKey => @access_key,
        :amount => @amount,
        :currencyCode => @currency_code,
        :lwaClientId => @lwa_client_id,
        :paymentAction => @payment_action,
        :returnURL => @return_url,
        :sellerId => @seller_id,
        :sellerNote => @seller_note,
        :sellerOrderId => @seller_order_id,
        :shippingAddressRequired => @shipping_address_required
      }

      # Sign the parameters and return the URL-encoded signatre
      signature = urlencode(sign_parameters(parameters))
      parameters[:signature] = signature
      parameters.to_json
    end


    private

    # Sets the encoding algorithm and creates a concatenated string from the parmaeters hash
    def sign_parameters(parameters)
      string_to_sign = nil
      algorithm    = "HmacSHA256"
      string_to_sign = calculate_string_to_sign(parameters)

      return sign(string_to_sign, algorithm)
    end

    # Adds headers string to concatenated parameters string
    def calculate_string_to_sign(parameters)
      data = "POST"
      data += "\n"
      data += "payments.amazon.com"
      data += "\n"
      data += "/"
      data += "\n"
      data += get_parameters_as_string(parameters)

      return data
    end

    # URL-encodes the parameters hash values and creates a concatenated string
    def get_parameters_as_string(parameters)
      query_parameters = []
      parameters.each { |key,value|
        s = urlencode(value)
        query_parameters += ["#{key}=#{s}"] }

      return query_parameters.join('&')
    end

    # URL encodes the hash values
    def urlencode(value)
      return CGI.escape(value).gsub('+','%20')
    end

    # Generates the signature that will be added back to the parameters hash
    def sign(data, algorithm)
      if algorithm == 'HmacSHA1'
        hash = 'sha1'
      elsif algorithm == 'HmacSHA256'
        hash = 'sha256'
      else
        raise "Non-supported signing method specified"
      end
      digest = OpenSSL::HMAC.digest(hash, @secret_key, data)
      return Base64.strict_encode64(digest)
    end

  end

end
