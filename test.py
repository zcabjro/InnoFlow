import os
import os.path
import subprocess
import sys

RUN_KARMA_TESTS = "karma start"
WEBPACK_FILE = "webpack.config.js"
WEBPACK_FILE_BACKUP = "webpack.config.js.backup"
WEBPACK_CONFIG = """module.exports = {
  module: {
    loaders: [
      {
        test: /\.js$/,
        loader: 'babel-loader',
        exclude: /node_modules/
      },
      {
        test: /\.vue$/,
        loader: 'vue-loader'
      }
    ]
  },
  resolve: {
    alias: {
      vue: 'vue/dist/vue.js'
    }
  }
}"""

backup = os.path.isfile(WEBPACK_FILE)

if backup:
  os.rename(WEBPACK_FILE, WEBPACK_FILE_BACKUP)

with open(WEBPACK_FILE, "w") as fo:
    fo.write(WEBPACK_CONFIG)
output = ""
try:
    output = subprocess.check_output(RUN_KARMA_TESTS, shell=True)
except subprocess.CalledProcessError as e:
    output = e.output
print output
os.remove(WEBPACK_FILE)

if backup:
  os.rename(WEBPACK_FILE_BACKUP, WEBPACK_FILE)