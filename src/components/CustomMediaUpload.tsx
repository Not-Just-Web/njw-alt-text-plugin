import React, { useEffect} from 'react';
import { Button } from 'antd';
import { CustomWindow } from '../helpers/types';

declare let window: CustomWindow;

export type CustomMediaUploadProps = {
	setCsvUrl: React.Dispatch<React.SetStateAction<string>>;
}

const CustomMediaUpload: React.FC<CustomMediaUploadProps> = ( {setCsvUrl}) => {

	useEffect(() => {
		if (typeof window.wp === 'undefined' || typeof window.wp.media === 'undefined') {
			console.log('The WordPress media library is not available.');
			return;
		}

		const frame = window.wp.media({
		title: 'Select or Upload Media',
		button: {
			text: 'Use this media',
		},
		multiple: false,
		library: {
			type: 'text/csv',
		},
		});

		frame.on('select', () => {
			const attachment = frame.state().get('selection').first().toJSON();

			setCsvUrl(attachment.url);
			// Check the file type
			if (attachment.mime !== 'text/csv') {
				// Show an error message
				alert('Please select a CSV file.');

				// Prevent the file from being used
				frame.state().get('selection').remove(attachment);
			}


		});

		document.getElementById('open-media-library')?.addEventListener('click', () => {
			frame.open();
		});
	}, []);

	return (
		<div>
			<h4> Please upload the csv file to media library or choose a csv file to proceed.</h4>
			<Button id="open-media-library">Open Media Library</Button>
		</div>
	);
};

export default CustomMediaUpload;
