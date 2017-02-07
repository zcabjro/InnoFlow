import os
import subprocess
import sys

RUN_KARMA_TESTS = "karma start"
WEBPACK_FILE = "webpack.config.js"
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

with open(WEBPACK_FILE, "w+") as fo:
    fo.write(WEBPACK_CONFIG)
output = ""
try:
    output = subprocess.check_output(RUN_KARMA_TESTS, shell=True)
except subprocess.CalledProcessError as e:
    output = e.output
print output
os.remove(WEBPACK_FILE)