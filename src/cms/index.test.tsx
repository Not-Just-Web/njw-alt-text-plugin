import React from 'react';
import ReactDOM from 'react-dom';
import Index from './entry'; // replace with actual path

jest.mock('react-dom', () => ({ render: jest.fn() }));

describe('Index', () => {
    it('renders without crashing', () => {
        const div = document.createElement('div');
        div.id = 'njw-media-react-plugin-page';
        document.body.appendChild(div);

        require('./index.tsx'); // replace with actual path

        expect(ReactDOM.render).toHaveBeenCalledWith(<Index />, div);
    });
});
