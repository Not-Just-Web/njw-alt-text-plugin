import React, { useState } from 'react';
import CustomTabs from '../components/CustomTabs';
import SearchFeature from '../components/SearchFeature';
import WelcomePanel from '../components/WelcomePanel';
import PostTypeSelector from '../components/PostTypeSelector';
import { defaultType, PostTypeKey } from '../helpers/types';
import MediaBulkUpdate from '../components/MediaBulkUpdate';

const App: React.FC = () => {
	const [postType, setPostType] = useState<PostTypeKey[]>(defaultType);
	const postTypeSelect = (value: PostTypeKey[]) => {
		// If 'media' is not in the selected items, add it back
		if (!value.includes('media')) {
			value.push('media');
		}
		setPostType(value);
	};

	const tabs = [
		{
			name: 'Media Finder',
			component: (
				<>
					<h3>Post Search Feature</h3>
					<SearchFeature supportedPostTypes={postType} />
				</>
			),
		},
		{
			name: 'Media Bulk Update',
			component: (
				<>
					<h3>Bulk Update </h3>
					<MediaBulkUpdate />
				</>
			),
		},
	];

	return (
		<>
			<WelcomePanel
				title="Welcome to NJW Media - Alt text  generator"
				description="This is a plugin to help you search media library and generate alt text for the media using generative AI."
			/>
			<div className="mx-1">
				<div>
					<span> Select Post Type : </span>
					<PostTypeSelector
						onSelect={postTypeSelect}
						defaults={defaultType}
					/>
				</div>
				<CustomTabs tabs={tabs} />
			</div>
		</>
	);
};
export default App;
