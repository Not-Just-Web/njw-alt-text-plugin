import React, {useEffect, useState} from 'react';
import Papa from 'papaparse';
import { useQueryClient } from 'react-query';
import { Select, Button, Row, Col, Progress } from 'antd';
import CustomMediaUpload from './CustomMediaUpload';
import { sendGetRequest } from '../helpers/api';
import {
	getApiEndpoint, replaceMediaId, defaultSite, getUrlForSite
} from '../helpers/conf';

const { Option } = Select;

type MediaBulkUpdateProps = {
	baseUrl?: string;
}

const MediaBulkUpdate = ({baseUrl = getUrlForSite(defaultSite) }: MediaBulkUpdateProps) => {
	const [mediaIdColumn, setMediaIdSelect] = useState<string | null>(null);
	const [mediaUrlColumn, setMediaUrlSelect] = useState<string | null>(null);
	const [mediaLength, setMediaLength] = useState<number>(5);
	const [csvData, setCsvData] = useState<any[]>([]);
	const [columns, setColumns] = useState<string[]>([]);
	const [csvUrl, setCsvUrl] = useState<string>('');
	const [progress, setProgress] = useState(0);

	const queryClient = useQueryClient();

	const handleMediaIdSelect = (value: string) => {
		setMediaIdSelect(value);
	}

	const handleLoopNumberSelect = (value: number) => {
		setMediaLength(value);
	}

	const handleMediaUrlSelect = (value: string) => {
		setMediaUrlSelect(value);
	}

	const availableNumber = ( total: number ) : number[] => {
		const numbers = [];
		for (let i = 10; i <= total; i += 10) {
			numbers.push(i);
		}

		numbers.push(total);
		return numbers;
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
					},
				});
			});
		}
	}, [csvUrl])

	const handleProcess = async () => {
		console.log('Processing CSV');
		console.log(csvData);

		const processedData = [];
		let loopItem = csvData;
		console.log('the length item:', csvData.length, mediaLength)
		console.log('Compare:', csvData.length < mediaLength)
		if( mediaLength < csvData.length ) {
			loopItem = csvData.slice(0, mediaLength);
		}
		console.log('the loop item:', loopItem)
		let count = 1;
		for (const row of loopItem) {
			if (mediaIdColumn !== null) { // Check if mediaIdColumn is not null
				const altTextApi = replaceMediaId(getApiEndpoint('open_ai_alt_text', baseUrl), row[mediaIdColumn]);
				console.log('Alt Text API:', altTextApi);
				const response = await queryClient.fetchQuery(['media', row], () => sendGetRequest(altTextApi, {}));
				setProgress(parseFloat(((count / loopItem.length) * 100).toFixed(2)));
				processedData.push(response);
				count++;
			}
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

			{columns.length &&
			<>
				<Row gutter={16}>
					<Col span={6} className="select-wrapper">
						<p>Media id Column</p>
						<Select placeholder="Select a column for media id" onChange={handleMediaIdSelect}>
							{columns.map((column) => (
							<Option key={column} value={column}>
								{column}
							</Option>
							))}
						</Select>
					</Col>
					<Col span={6} className="select-wrapper">
						<p>Media Url Column</p>
						<Select placeholder="Select a column for media url" onChange={handleMediaUrlSelect}>
							{columns.map((column) => (
							<Option key={column} value={column}>
								{column}
							</Option>
							))}
						</Select>
					</Col>
					<Col span={6} className="select-wrapper">
						<p> Select Limit </p>
						<Select placeholder="Select a column for available numbers" onChange={handleLoopNumberSelect}>
							{availableNumber(csvData.length).map((number) => (
								<Option key={number} value={number}>
									{number}
								</Option>
							))}
						</Select>
					</Col>
					<Col span={6} className="select-wrapper">
						<p> &nbsp;</p>
						{mediaIdColumn && mediaUrlColumn && mediaLength &&
							<Button onClick={handleProcess}>Process CSV</Button>
						}
					</Col>
				</Row>
				{progress > 0 &&
					<Row>
						<Col span={24}>
							<Progress percent={progress} />
						</Col>
					</Row>
				}
			</>
			}
		</div>
	)
}

export default MediaBulkUpdate
