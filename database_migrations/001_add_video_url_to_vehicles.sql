-- Add video_url field to vehicles table
-- Run this migration if your database was created before video support was added

ALTER TABLE vehicles ADD COLUMN video_url VARCHAR(500) AFTER description;

-- Update: Add comment for clarity
COMMENT ON COLUMN vehicles.video_url IS 'YouTube or Vimeo embed URL for vehicle video';
