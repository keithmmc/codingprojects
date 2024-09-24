from flask import Blueprint, render_template, request, redirect, url_for, session, flash
from services.user_service import UserService

from models.user_dao import UserDAO
from models.music_dao import MusicDAO
from app import mysql

auth_bp = Blueprint('auth', __name__)

music_dao = MusicDAO(mysql)
music_service = MusicService(music_dao)

user_dao = UserDAO(mysql)
user_service = UserService(user_dao)

# Register route
@auth_bp.route('/register', methods=['GET', 'POST'])
def register():
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']
        user_service.register_user(username, password)
        flash('Registration successful. You can now log in.', 'success')
        return redirect(url_for('auth.login'))
    return render_template('register.html')

# Login route
@auth_bp.route('/login', methods=['GET', 'POST'])
def login():
    if request.method == 'POST':
        username = request.form['username']
        password = request.form['password']
        user = user_service.validate_user(username, password)
        if user:
            session['user_id'] = user['id']
            session['username'] = user['username']
            flash('Login successful.', 'success')
            return redirect(url_for('index'))
        else:
            flash('Invalid credentials. Please try again.', 'danger')
    return render_template('login.html')

# Logout route
@auth_bp.route('/logout')
def logout():
    session.pop('user_id', None)
    session.pop('username', None)
    flash('You have been logged out.', 'info')
    return redirect(url_for('auth.login'))

@auth_bp.route('/artists')
def artists():
    artists = music_service.get_artists()
    return render_template('artists.html', artist=artists)






    