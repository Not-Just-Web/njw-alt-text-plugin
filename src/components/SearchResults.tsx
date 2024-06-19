import React, { useState, useEffect } from 'react';
import { ErrorType, QueryResult, QueryParamTypes } from '../helpers/types';
import { useQuery } from 'react-query';
import { sendGetRequest } from '../helpers/api';
import { Table, Typography } from 'antd';
import {
	getApiEndpoint,
	replacePostType,
} from '../helpers/conf';
import Exporter from './Exporter';

import { generateTableColumns } from './Columns';

type SearchResultsProps = {
	postType: string;
	keyword: string;
	baseUrl: string;
};

const SearchResults: React.FC<SearchResultsProps> = ({
	postType,
	baseUrl,
	keyword,
}) => {
	const [page, setPage] = useState<number>(1);
	const [perPage, setPerPage] = useState<number>(10);
	const [sortField, setSortField] = useState<string>('date');
	const [sortOrder, setSortOrder] = useState<'asc' | 'desc'>('desc');

	const SEARCH_RESULTS_URL = replacePostType(
		getApiEndpoint('dynamic_post_type_url', baseUrl),
		postType
	);
	const {
		isLoading,
		isFetching,
		error,
		data: searchResponse,
		refetch,
	} = useQuery<QueryResult, ErrorType>(['searchResults', postType], () => {
		const param: QueryParamTypes = {
			per_page: perPage,
			page,

			orderby: sortField,
			order: sortOrder,
		};
		if (keyword) {
			// eslint-disable-next-line
			param.search = `"${keyword}"`;
		}

		return sendGetRequest(SEARCH_RESULTS_URL, param)
	});

	const { Text } = Typography;

	useEffect(() => {
		refetch();
	}, [page, keyword, perPage, sortField, sortOrder]);



	const { data: searchData, header } = searchResponse || ({} as QueryResult);

	const pageSizes = [10, 20, 30, 40, 50, 100];
	const columns = generateTableColumns(
		postType,
		setSortField,
		setSortOrder,
		perPage,
		sortOrder,
		baseUrl,
		page
	);
	const totalResults = header ? header.get('x-wp-total') : 0;
	const totalPages = header ? header.get('x-wp-totalpages') : 0;
	const exportKeys = ['id', 'link', 'title', 'date', 'edit_link'];

	const formattedKeyword = keyword.replace(/\s/g, '_');
	const currentDate = new Date().toISOString().split('T')[0];
	const fileName = `${postType}_${formattedKeyword}--FOUND_${currentDate}`;

	return (
		<div className="mt-2">
			<hr />
			{error && <div className="error">Error: {error.message}</div>}
			{ isLoading && <div className="loading">Loading...</div>}
			<h2> {postType.charAt(0).toUpperCase() + postType.slice(1)} </h2>
			{!isLoading && (
				<>
					<div className="card mx-auto p-1 my-2">
						<div className="flex">
							<div className="mr-2 ">
								{' '}
								Total Results:{' '}
								<Text type="success">{totalResults}</Text>{' '}
							</div>
							<div className="ml-2">
								{' '}
								Total Pages:{' '}
								<Text type="danger">{totalPages} </Text>{' '}
							</div>
						</div>
					</div>
				</>
			)}

			<Table
				dataSource={searchData}
				loading={isLoading || isFetching}
				columns={columns}
				pagination={{
					total: totalResults,
					pageSize: perPage,
					showSizeChanger: true,
					pageSizeOptions: pageSizes,
					onChange: (cpage: number) => {
						setPage(cpage);
					},
					onShowSizeChange: (current: number, size: number) => {
						setPerPage(size);
					},
				}}
				footer={() =>
					searchData && searchData.length ? (
						<Exporter
							exportKeys={exportKeys}
							fileName={fileName}
							baseUrl={baseUrl}
							queryKey="searchResults"
							totalPages={totalPages}
							url={SEARCH_RESULTS_URL}
							params={{ search: keyword, per_page: perPage, orderby: sortField, order: sortOrder}}
						/>
					) : (
						''
					)
				}
				rowKey="id"
			/>
		</div>
	);
};

export default SearchResults;
