#!/bin/bash

# Check if a directory has been provided
if [ "$#" -ne 1 ]; then
    echo "Usage: $0 target_directory"
    exit 1
fi

TARGET_DIRECTORY=$(realpath $1)

# Get the name of the current directory
SOURCE_DIRECTORY_NAME=$(basename $PWD)

# Check if the target subdirectory already exists
if [ ! -d "$TARGET_DIRECTORY/$SOURCE_DIRECTORY_NAME" ]; then
    # Create the target subdirectory if it doesn't exist
    mkdir -p "$TARGET_DIRECTORY/$SOURCE_DIRECTORY_NAME"
	# Print the new destination directory
	echo "New destination directory: $TARGET_DIRECTORY/$SOURCE_DIRECTORY_NAME"
else
    echo "Directory $TARGET_DIRECTORY/$SOURCE_DIRECTORY_NAME already exists. Syncing."
fi

# Use rsync to sync the current directory to the target subdirectory
rsync -a "$PWD/" "$TARGET_DIRECTORY/$SOURCE_DIRECTORY_NAME"

# Use fswatch to monitor the current directory for changes
fswatch -o . | while read f; do
  echo "File changed: $f"
  # Sync the directories again after a change
  rsync -a "$PWD/" "$TARGET_DIRECTORY/$SOURCE_DIRECTORY_NAME"
done
