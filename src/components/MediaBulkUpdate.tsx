import React, {useEffect, useState} from 'react';
import Papa from 'papaparse';
import { useQueryClient } from 'react-query';
import { Button, Row, Col, Progress } from 'antd';
import CustomMediaUpload from './CustomMediaUpload';
import { sendGetRequest } from '../helpers/api';
import {
	getApiEndpoint, replaceMediaId, defaultSite, getUrlForSite
} from '../helpers/conf';

type MediaBulkUpdateProps = {
	baseUrl?: string;
}

const MediaBulkUpdate = ({baseUrl = getUrlForSite(defaultSite) }: MediaBulkUpdateProps) => {
	const [mediaIdColumn, setMediaIdSelect] = useState<string >('');
	const [mediaUrlColumn, setMediaUrlSelect] = useState<string>('');
	const [mediaLength, setMediaLength] = useState<number>(10);
	const [responseData, setResponseData] = useState<any[]>([]);
	const [csvData, setCsvData] = useState<any[]>([]);
	const [columns, setColumns] = useState<string[]>([]);
	const [csvUrl, setCsvUrl] = useState<string>('');
	const [progress, setProgress] = useState(0);

	const queryClient = useQueryClient();

	const handleMediaIdSelect = (e: React.ChangeEvent<HTMLSelectElement>) => {
		setMediaIdSelect(e.target.value);
	}

	const handleLoopNumberSelect = (e: React.ChangeEvent<HTMLSelectElement>) => {
		setMediaLength(parseInt(e.target.value));
	}

	const handleMediaUrlSelect = (e: React.ChangeEvent<HTMLSelectElement>) => {
		setMediaUrlSelect(e.target.value);
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
		const processedData = [];
		let loopItem = csvData;
		if( mediaLength < csvData.length ) {
			loopItem = csvData.slice(0, mediaLength);
		}
		let count = 1;
		for (const row of loopItem) {
			if (mediaIdColumn !== null) { // Check if mediaIdColumn is not null
				const altTextApi = replaceMediaId(getApiEndpoint('open_ai_alt_text', baseUrl), row[mediaIdColumn]);
				const mediaUrl =  mediaUrlColumn !== null ? row[mediaUrlColumn] : '';
				const response = await queryClient.fetchQuery(['media', row], () => sendGetRequest(altTextApi, {
					mediaUrl: mediaUrl || ''
				}));
				setProgress(parseFloat(((count / loopItem.length) * 100).toFixed(2)));
				processedData.push(response.data);
				setResponseData(processedData)
				count++;
			}
		}
	};

	return (
		<div>
			<CustomMediaUpload setCsvUrl={setCsvUrl} />
			{ csvUrl &&
				<p title="csvURlItem"> Url of the csv file :
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
						<select data-testid="media_id_select" value={mediaIdColumn} onChange={handleMediaIdSelect}>
							{columns.map((column, index) => (
							<option key={index} value={column}>
								{column}
							</option>
							))}
						</select>
					</Col>
					<Col span={6} className="select-wrapper">
						<p>Media Url Column</p>
						<select data-testid="media_url_select" value={mediaUrlColumn} onChange={handleMediaUrlSelect}>
							{columns.map((column, index) => (
							<option key={index} value={column}>
								{column}
							</option>
							))}
						</select>
					</Col>
					<Col span={6} className="select-wrapper">
						<p> Select Limit </p>
						<select data-testid="media_limit_select"  value={mediaLength} onChange={handleLoopNumberSelect}>
							{availableNumber(csvData.length).map((number, index) => (
								<option key={index} value={number}>
									{number}
								</option>
							))}
						</select>
					</Col>
					<Col span={6} className="select-wrapper">
						<p> &nbsp;</p>
						{(mediaIdColumn || mediaUrlColumn) && mediaLength &&
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

			{responseData && responseData.map((item, index) => (
				<pre key={index}>{JSON.stringify(item, null, 2)}</pre>
			))}
		</div>
	)
}

export default MediaBulkUpdate
