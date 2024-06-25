import React from 'react';
import { render, screen } from '@testing-library/react';
import CustomTable from './CustomTable';

describe('CustomTable', () => {
  it('renders the table correctly', () => {
    const mockProps = {
      isLoading: false,
      postType: 'post',
      totalResults: 10,
      totalPages: 2,
      searchData: [{ id: 1, name: 'Test' }],
      isFetching: false,
      columns: [{ title: 'Name', dataIndex: 'name', key: 'name' }],
      perPage: 5,
      pageSizes: [5, 10, 20],
      setPage: jest.fn(),
      setPerPage: jest.fn(),
      footer: () => <div>Footer</div>,
    };

    render(<CustomTable {...mockProps} />);

    expect(screen.getByText('Post')).toBeDefined();
    expect(screen.getByText('Total Results:')).toBeDefined();
    expect(screen.getByText('Total Pages:')).toBeDefined();
    expect(screen.getByText('Test')).toBeDefined();
    expect(screen.getByText('Footer')).toBeDefined();
  });
});
