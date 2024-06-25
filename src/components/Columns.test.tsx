<<<<<<< Updated upstream
  import React from 'react';
=======
import React from 'react';
>>>>>>> Stashed changes
import { generateTableColumns } from './Columns';

describe('generateTableColumns', () => {
  const setSortField = jest.fn();
  const setSortOrder = jest.fn();
  const perPage = 10;
  const sortOrder = 'asc';
  const baseUrl = 'http://test.com';
  const page = 1;

  it('should return MediaColumn when postType is media', () => {
    const result = generateTableColumns('media', setSortField, setSortOrder, perPage, sortOrder, baseUrl, page);
    expect(result[0].title).toBe('S/N');
    expect(result[1].title).toBe('Media Image');
    expect(result[2].title).toBe('Media Id');
    // Add more assertions as needed
  });

  it('should return PostColumn when postType is not media', () => {
    const result = generateTableColumns('post', setSortField, setSortOrder, perPage, sortOrder, baseUrl, page);
    expect(result[0].title).toBe('S/N');
    expect(result[1].title).toBe('Page Id');
    expect(result[2].title).toBe('Title');
    // Add more assertions as needed
  });
});
