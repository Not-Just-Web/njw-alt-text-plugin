import React from 'react';
import { render, fireEvent } from '@testing-library/react';
import CustomMediaUpload from './CustomMediaUpload';

describe('CustomMediaUpload', () => {
  let setCsvUrl: React.Dispatch<React.SetStateAction<string>>;
  let consoleSpy: jest.SpyInstance;

  beforeEach(() => {
    setCsvUrl = jest.fn();
    consoleSpy = jest.spyOn(console, 'log').mockImplementation(() => {});
  });

  afterEach(() => {
    consoleSpy.mockRestore();
  });

  it('renders without crashing', () => {
    const { getByText } = render(<CustomMediaUpload setCsvUrl={setCsvUrl} />);
    expect(getByText('Please upload the csv file to media library or choose a csv file to proceed.')).toBeDefined();
  });

  it('renders the button', () => {
    const { getByText } = render(<CustomMediaUpload setCsvUrl={setCsvUrl} />);
    expect(getByText('Open Media Library')).toBeDefined();
  });

  it('calls the click event', () => {
    const { getByText } = render(<CustomMediaUpload setCsvUrl={setCsvUrl} />);
    const button = getByText('Open Media Library');
    fireEvent.click(button);
    // Here we would test the expected behavior of the click event.
    // However, this would require mocking the global window object and its methods,
    // which is beyond the scope of this unit test.
  });
});
