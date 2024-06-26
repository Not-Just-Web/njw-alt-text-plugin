//  eslint-disable-next-line @typescript-eslint/no-unused-vars
export type apiEndpointType = {
	method: string;
	name: string;
	prefix?: string;
	url?: string | null | undefined;
	body?: any;
	headers?: any;
};

export type QueryParamTypes = {
	per_page?: number;
	page?: number;
	mediaUrl?: string;
	search?: string;
	orderby?: string;
	order?: 'asc' | 'desc';
};

export interface CustomWindow extends Window {
	wp: any;
	njwVars: {
		nonce: string;
		accessKey: string;
		pluginRoute: string;
	}
}

export interface MediaType {
	length: number;
	url: string;
	id: number;
}

export interface RenderProps {
	open: () => void;
}

export interface SearchResultType {
	id: number;
	date: Date;
	title: {
		rendered: string;
	};
	content: {
		rendered: string;
	};
	excerpt: {
		rendered: string;
	};
	guid: {
		rendered: string;
	};
	alt_text?: string;
	slug: string;
	link: string;
	count: number;
}

export interface QueryResult {
	data: SearchResultType[];
	header: any;
}

type PostData = {
	name: string;
	slug: string;
	rest_base: string;
};

export type PostTypeKey =
	| 'posts'
	| 'recipe'
	| 'review'
	| 'restaurant'
	| 'media'
	| 'product'
	| 'pages'
	| 'post'
	| 'page';
export const postTypeKeys: PostTypeKey[] = [
	'post',
	'recipe',
	'review',
	'restaurant',
	'product',
	'page',
];

export const defaultType: PostTypeKey[] = ['media'];

type PostTypes = {
	[K in PostTypeKey]?: PostData;
};

export interface PostTypeResult {
	data: PostTypes;
	header: any;
}

export interface ErrorType {
	message: string;
}
