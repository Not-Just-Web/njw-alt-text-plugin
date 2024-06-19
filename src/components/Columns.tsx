import React from 'react';
import { decode } from 'html-entities';
import { SearchResultType } from "../helpers/types";
import {
	replacePostId,
	getApiEndpoint,
} from '../helpers/conf';
import { Image } from 'antd';

export const generateTableColumns = (
	postType: string,
	setSortField: (field: string) => void,
	setSortOrder: (order: 'asc' | 'desc') => void,
	perPage:number,
	sortOrder: 'asc' | 'desc',
	baseUrl: string,
	page:number
) => {
	const MediaColumn = [
		{
			title: 'S/N',
			dataIndex: 'serialNumber',
			key: 'serialNumber',
			render: (text: string, record: SearchResultType, index: number) => (
				<>{(page - 1) * perPage + (index + 1)}</>
			),
		},
		{
			title: 'Media Image',
			dataIndex: 'guid',
			key: 'guid',
			sorter: false,
			render: (text: string, record: SearchResultType) => {
				const { rendered: guidRendered } = record.guid;
				return <Image width={200} src={guidRendered}  alt={record?.alt_text}/>;
			},
		},
		{
			title: 'Media Id',
			dataIndex: 'id',
			key: 'id',
			sorter: true,
			onHeaderCell: () => ({
				onClick: () => {
					setSortField('id');
					setSortOrder(sortOrder === 'asc' ? 'desc' : 'asc');
				},
			}),
			render: (text: string, record: SearchResultType) => {
				const { id} = record;
				return (
					<>
						<a
							href={record.link ?? '#'}
							key={record.id}
							target="_blank"
							rel="noopener noreferrer"
						>
							{id}
						</a>
					</>
				);
			},
		},
		{
			title: 'Alt Text',
			dataIndex: 'alt_text',
			sorter: true,
			onHeaderCell: () => ({
				onClick: () => {
					setSortField('alt_text');
					setSortOrder(sortOrder === 'asc' ? 'desc' : 'asc');
				},
			}),
			render: (text: string, record: SearchResultType) => {
				const { alt_text: altText} = record;
				return (
					<>

					{altText && altText.length > 0 ? altText : ' --- NONE ---'}

					</>
				);
			},
		},
		{
			title: 'Published Date',
			dataIndex: 'date',
			key: 'date',
			sorter: true,
			onHeaderCell: () => ({
				onClick: () => {
					setSortField('date');
					setSortOrder(sortOrder === 'asc' ? 'desc' : 'asc');
				},
			}),
			render: (text: string) => (
				<>
					{new Date(text).toLocaleString('en-AU', {
						year: 'numeric',
						month: 'short',
						day: 'numeric',
						hour: 'numeric',
						minute: 'numeric',
						hour12: true,
						timeZone: 'Australia/Sydney',
					})}
				</>
			),
		},
		{
			title: 'Actions',
			key: 'actions',
			render: (text:string, record: SearchResultType) => (
				<>
					<a
						href={replacePostId(
							getApiEndpoint('admin_edit_url', baseUrl),
							record.id
						)}
						key={record.id}
						target="_blank"
						rel="noopener noreferrer"
					>
						<span className="dashicons dashicons-edit"></span> Edit
					</a>
			</>
			),
		}
	];


	const PostColumn = [
		{
			title: 'S/N',
			dataIndex: 'serialNumber',
			key: 'serialNumber',
			render: (text: string, record: SearchResultType, index: number) => (
				<>{(page - 1) * perPage + (index + 1)}</>
			),
		},
		{
			title: 'Page Id',
			dataIndex: 'id',
			key: 'id',
			sorter: true,
			onHeaderCell: () => ({
				onClick: () => {
					setSortField('id');
					setSortOrder(sortOrder === 'asc' ? 'desc' : 'asc');
				},
			}),
		},
		{
			title: 'Title',
			dataIndex: 'title',
			sorter: true,
			onHeaderCell: () => ({
				onClick: () => {
					setSortField('title');
					setSortOrder(sortOrder === 'asc' ? 'desc' : 'asc');
				},
			}),
			render: (text: string, record: SearchResultType) => {
				const { rendered: titleRendered} = record.title;
				return (
					<>
						<a
							href={record.link ?? '#'}
							key={record.id}
							target="_blank"
							rel="noopener noreferrer"
						>
							{decode(titleRendered)}
						</a>
					</>
				)
			},
		},
		{
			title: 'Published Date',
			dataIndex: 'date',
			key: 'date',
			sorter: true,
			onHeaderCell: () => ({
				onClick: () => {
					setSortField('date');
					setSortOrder(sortOrder === 'asc' ? 'desc' : 'asc');
				},
			}),
			render: (text: string) => (
				<>
					{new Date(text).toLocaleString('en-AU', {
						year: 'numeric',
						month: 'short',
						day: 'numeric',
						hour: 'numeric',
						minute: 'numeric',
						hour12: true,
						timeZone: 'Australia/Sydney'
					})}
				</>
			),
		},
		{
			title: 'Actions',
			key: 'actions',

			render: (text: string, record: SearchResultType) => (
				<>
					<a
						href={replacePostId(
							getApiEndpoint('admin_edit_url', baseUrl),
							record.id
						)}
						key={record.id}
						target="_blank"
						rel="noopener noreferrer"
					>
						<span className="dashicons dashicons-edit"></span>
					</a>
				</>
			),
		},
	];

	if (postType === 'media') {
		return MediaColumn;
	} else {
		return PostColumn;
	}
}
