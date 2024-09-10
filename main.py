from flask import Flask, request
from flask_mail import Mail, Message
from random import randint

app = Flask(__name__)

# Mail configuration
app.config["MAIL_SERVER"] = 'smtp.gmail.com'
app.config["MAIL_PORT"] = 465
app.config["MAIL_USERNAME"] = 'pradiptatalukdar2@gmail.com'  # Replace with your email
app.config['MAIL_PASSWORD'] = 'vfkw nqoz uliy jxgp'          # Replace with your password
app.config['MAIL_USE_TLS'] = False
app.config['MAIL_USE_SSL'] = True

mail = Mail(app)
otp_store = {}

@app.route('/send_otp')
def send_otp():
    email = request.args.get('email')
    otp = randint(100000, 999999)
    otp_store[email] = otp
    try:
        msg = Message('Your OTP for Verification', sender='your_email@gmail.com', recipients=[email])
        msg.body = f'Your OTP is {otp}. Please use this to complete your registration.'
        mail.send(msg)
        return "Success"
    except Exception as e:
        return str(e)

if __name__ == "__main__":
    app.run(debug=True)
