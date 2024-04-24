#!/bin/bash

# This is a simple bash script for MyPackage
# It prints a message

echo "Hello from MyPackage!"

php_output=$(php ../scripts/sample.php)

echo "PHP script output:"
echo "$php_output"

# Keep the terminal window open in Visual Studio Code
read -p "Press Enter to close the window..." 
