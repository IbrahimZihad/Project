from flask import Flask, jsonify, render_template
from flask_cors import CORS
import mysql.connector

app = Flask(__name__)
CORS(app)

# Connect to MySQL
db = mysql.connector.connect(
    host="localhost",
    user="root",
    password="",
    database="taskmanager"
)

@app.route('/')
def index():
    return render_template('index.html')

@app.route('/api/work-items')
def get_work_items():
    cursor = db.cursor(dictionary=True)
    cursor.execute("""
        SELECT 
            SUM(status = 'To Do') AS todo,
            SUM(status = 'In Progress') AS inProgress,
            SUM(status = 'Done') AS done 
        FROM tasks
    """)
    row = cursor.fetchone()
    row['total'] = (row['todo'] or 0) + (row['inProgress'] or 0) + (row['done'] or 0)
    return jsonify(row)

if __name__ == '__main__':
    app.run(debug=True)
