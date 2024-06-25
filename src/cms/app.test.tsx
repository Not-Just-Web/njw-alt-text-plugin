import React from 'react';
import { render } from '@testing-library/react';
import App from './app'; // replace with actual path

import { CustomWindow } from "../helpers/types";

jest.mock('../components/CustomTabs', () => () => <div>CustomTabs</div>); // Mock the CustomTabs component
jest.mock('../components/PostTypeSelector', () => () => <>PostTypeSelector</>); // Mock the PostTypeSelector component
jest.mock('../components/WelcomePanel', () => () => <div>WelcomePanel</div>); // Mock the WelcomePanel component
jest.mock('../components/SearchFeature', () => () => <div>SearchFeature</div>); // Mock the SearchFeature component

declare let window: CustomWindow;

describe('App', () => {
	beforeAll(() => {
        // Define window.njwVars before the tests run
        window.njwVars = {
            pluginRoute: 'njw/v1',
			nonce: 'your-nonce',
			accessKey: 'your-access',
            // Add any other properties that njwVars should have
        };
    });
    it('renders correctly', () => {
        const { getByText } = render(<App />);

        expect(getByText('WelcomePanel')).toBeDefined();
        expect(getByText('CustomTabs')).toBeDefined();
        expect(getByText('PostTypeSelector')).toBeDefined();
    });
});
