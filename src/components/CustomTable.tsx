import React from 'react';
import { Table, Typography } from 'antd';
import { ErrorType, QueryResult } from '../helpers/types';

type CustomTableProps = {
	error?: ErrorType | null;
	isLoading: boolean;
	postType: string;
	totalResults?: number;
	totalPages?: number;
	searchData: any[] | QueryResult[];
	isFetching: boolean;
	columns: any[];
	perPage: number;
	pageSizes: number[];
	setPage: (page: number) => void;
	setPerPage: (perPage: number) => void;
	footer: () => JSX.Element | string;
};

const CustomTable = (props: CustomTableProps) => {
	const { Text } = Typography;
	const {
		error,
		isLoading,
		postType,
		totalResults,
		totalPages,
		searchData,
		isFetching,
		columns,
		perPage,
		pageSizes,
		setPage,
		setPerPage,
		footer
	} = props;

	return (
		<div className="mt-2">
			<hr />
			{error && <div className="error">Error: {error.message}</div>}
			{isLoading && <div className="loading">Loading...</div>}
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
				footer={ footer}
				rowKey="id"
			/>
		</div>
	);
};

export default CustomTable;
