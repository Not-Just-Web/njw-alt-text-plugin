// MediaBulkUpdate.test.tsx
import { render, fireEvent, screen, waitFor } from '@testing-library/react';
import userEvent from '@testing-library/user-event';
import '@testing-library/jest-dom';
import MediaBulkUpdate from './MediaBulkUpdate';
import fetchMock from 'jest-fetch-mock';
import Papa from 'papaparse';

import React from 'react';

const changeInputValue = async (testId: string, value: string) => {
  const select = await waitFor(() => screen.getByTestId(testId));
	userEvent.selectOptions(select, value);
}

jest.mock('react-query', () => ({
  ...jest.requireActual('react-query'),
  useQueryClient: jest.fn().mockReturnValue({
	fetchQuery: jest.fn().mockResolvedValue({ data: 'mockData' })
  }),
}));


jest.mock('./CustomMediaUpload', () => {
  return ({ setCsvUrl }: { setCsvUrl: any}) => {
    // Simulate file upload and state update
    const handleFileUpload = (event: React.ChangeEvent<HTMLInputElement>) => {
      if (event.target.files?.length) {
		const csvUrl = 'https://run.mocky.io/v3/1ed25643-5440-43fc-9c24-a4c55b41ce17';
        // Set the csvUrl state
        setCsvUrl(csvUrl);
      }
    };

    return (
      <div>
        <h4>Please upload the csv file to media library or choose a csv file to proceed.</h4>
		<input
			type="file"
			id="open-media-library"
			role='customMediaUpload'
			data-testid="open-media-library"
			onChange={handleFileUpload}
		/>
      </div>
    );
  };
});

// const useQueryClient = jest.fn().mockReturnValue({ fetchQuery: jest.fn() });

jest.mock('../helpers/api', () => ({
  sendGetRequest: jest.fn().mockResolvedValue({ data: 'Mocked Data' }),
}));

describe('MediaBulkUpdate component', () => {
  beforeEach(() => {
	jest.spyOn(console, 'error').mockImplementation(() => {});
	fetchMock.enableMocks(); // Enable mocking for all tests.
	jest.spyOn(Papa, 'parse');

    (Papa.parse as jest.Mock).mockImplementation((csv, config) => {
		const data = [];
		for (let i = 1; i <= 50; i++) {
			data.push([i, `http://dummyurl.com/${i}`]);
		}

		// Call the complete function with the mock results.
		config.complete({
			data,
			errors: [],
			meta: { fields: ['media_id', 'media_url'] },
		});

		return {
			header: true,
			skipEmptyLines: true,
			complete: config.complete,
			data,
			meta: { fields: ['media_id', 'media_url'] },
		};
	});
  });

  afterEach(() => {
	fetchMock.resetMocks(); // Reset mocks after each test
  });

  it('renders the component correctly', () => {
    render(<MediaBulkUpdate />);

    expect(screen.getAllByRole('customMediaUpload')).toBeDefined(); // Use toBeDefined
  });

  it('shows CSV URL after upload', async () => {
    render(<MediaBulkUpdate />);

    const upload = screen.getByTestId('open-media-library'); // Assuming CustomMediaUpload has a testId
    await userEvent.upload(upload, new File(['test.csv'], 'test.csv', { type: 'text/csv' }));

    expect(screen.getAllByTitle('csvURlItem')); // Use toBeDefined
    expect(screen.getByRole('link').getAttribute('href')).toMatch(/run.mocky.io/);
  });

  it('enables process button when media id and limit are selected', async () => {
	// Use a state hook in your test

    render(<MediaBulkUpdate />);

    const upload = screen.getByTestId('open-media-library');
    // await userEvent.upload(upload, new File(['test.csv'], 'test.csv', { type: 'text/csv' }));
	fireEvent.change(upload, { target: { files: [new File(['test.csv'], 'test.csv', { type: 'text/csv' })] } });

	await changeInputValue('media_id_select', 'media_id');
	await changeInputValue('media_url_select', 'media_url');
	await changeInputValue('media_limit_select', '10');

    expect(screen.getByRole('button', { name: /Process CSV/i })).toBeDefined(); // Use toBeEnabled
  });

  it('calls process function and displays progress', async () => {
	// Use a state hook in your test

    render(<MediaBulkUpdate />);

    const upload = screen.getByTestId('open-media-library');
    await userEvent.upload(upload, new File(['test.csv'], 'test.csv', { type: 'text/csv' }));

	await changeInputValue('media_id_select', 'media_id');
	await changeInputValue('media_url_select', 'media_url');
	await changeInputValue('media_limit_select', '10');

	const processButton = await waitFor(() => screen.getByRole('button', { name: /Process CSV/i }), { timeout: 10000 });

	await userEvent.click(processButton);

	expect(screen.getByRole('progressbar')).toBeDefined(); // Use toBeDefined
  });

  it('displays processed data after successful processing', async () => {

    render(<MediaBulkUpdate />);

    const upload = await screen.findByTestId('open-media-library');
    // await userEvent.upload(upload, new File(['test.csv'], 'test.csv', { type: 'text/csv' }));
	fireEvent.change(upload, { target: { files: [new File(['test.csv'], 'test.csv', { type: 'text/csv' })] } });

	await changeInputValue('media_id_select', 'media_id');
	await changeInputValue('media_url_select', 'media_url');
	await changeInputValue('media_limit_select', '10');

	const processButton = await waitFor(() => screen.getByRole('button', { name: /Process CSV/i }));

	userEvent.click(processButton);

  });
});
