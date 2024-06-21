import React, {useEffect, useState} from 'react';
import Papa from 'papaparse';
import { useQueryClient } from 'react-query';
import { Select, Button } from 'antd';
import CustomMediaUpload from './CustomMediaUpload';
import { sendGetRequest } from '../helpers/api';
import {
	getApiEndpoint, replaceMediaId
} from '../helpers/conf';

const { Option } = Select;

type MediaBulkUpdateProps = {
	baseUrl: string;
}

const MediaBulkUpdate = ({baseUrl}: MediaBulkUpdateProps) => {
	const [mediaIdColumn, setMediaIdSelect] = useState<string | null>(null);
	const [mediaUrlColumn, setMediaUrlSelect] = useState<string | null>(null);
	const [csvData, setCsvData] = useState<any[]>([]);
	const [columns, setColumns] = useState<string[]>([]);
	const [csvUrl, setCsvUrl] = useState<string>('');

	const altTextApi = replaceMediaId( getApiEndpoint('open_ai_alt_text', baseUrl));

	const handleMediaIdSelect = (value: string) => {
		setMediaIdSelect(value);
	}

	const handleMediaUrlSelect = (value: string) => {
		setMediaUrlSelect(value);
	}

	useEffect(() => {
		if( csvUrl && csvUrl.length > 0 ) {

			fetch(csvUrl)
				.then((response) => response.text())
				.then((csv) => {
				Papa.parse(csv, {
					header: true,
					skipEmptyLines: true,
					complete: (results) => {
					setColumns(results.meta.fields as string[]);
					setCsvData(results.data);
				}});
			});
		}
	}, [csvUrl])

	const handleProcess = async () => {
		console.log('Processing CSV');
		console.log(csvData);

		const queryClient = useQueryClient();

		const processedData = [];
		for (const row of csvData.slice(0, 4)) {
			const response = await queryClient.fetchQuery(['media', row], () => sendGetRequest(altTextApi, { }));
			processedData.push(response);
		}

		console.log('Processed Data:', processedData);
	};

	return (
		<div>
			<CustomMediaUpload setCsvUrl={setCsvUrl} />
			{ csvUrl &&
				<p> Url of the csv file :
					<a href={csvUrl ?? '#'} target="_blank" rel="noopener noreferrer">
						{csvUrl}
					</a>
				</p>
			}

			{columns.length > 0 && (
			<>
				<Select placeholder="Select a column for media id" onChange={handleMediaIdSelect}>
					{columns.map((column) => (
					<Option key={column} value={column}>
						{column}
					</Option>
					))}
				</Select>
				<Select placeholder="Select a column for media url" onChange={handleMediaUrlSelect}>
					{columns.map((column) => (
					<Option key={column} value={column}>
						{column}
					</Option>
					))}
				</Select>
			</>
			)}

			{mediaIdColumn && mediaUrlColumn && (
				<Button onClick={handleProcess}>Process CSV</Button>
			)}
		</div>
	)
}

export default MediaBulkUpdate
