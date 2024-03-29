#!/usr/bin/env bash
set -e # Exit immediately if a command exits with a non-zero status.

# Save the current script directory
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"

# Define the path of a temporary directory for storing the generated SDK code
TEMP_DIR="$SCRIPT_DIR/temp_sdk"

# Create the temporary directory
mkdir -p $TEMP_DIR

# Change to the project root directory to run the SDK generator
cd ..

# Run the SDK generator command
nextlove-sdk-generator generate php $TEMP_DIR

# Return to the script directory
cd "$SCRIPT_DIR"

# Check if the temp directory was created successfully
if [ ! -d "$TEMP_DIR" ]; then
    echo "Failed to generate SDK"
    exit 1
fi

# Replace the 'src' directory
rm -rf ../src
mv $TEMP_DIR/src ../src

# Copy any other files that don't exist in the root directory
for file in $TEMP_DIR/*; do
    if [ ! -e "../$(basename "$file")" ]; then
        mv "$file" ..
    fi
done

# Remove the temporary directory
rm -rf $TEMP_DIR

echo "SDK generation complete."
