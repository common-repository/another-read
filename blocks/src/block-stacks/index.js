import { registerBlockType } from '@wordpress/blocks';
import ServerSideRender from '@wordpress/server-side-render';
import { __ } from '@wordpress/i18n';
import {
    CheckboxControl,
    TextControl,
    Panel,
    PanelBody,
    PanelRow,
    SelectControl
} from '@wordpress/components'
import { 
    useBlockProps,
    InspectorControls,
} from '@wordpress/block-editor';
import apiFetch from '@wordpress/api-fetch';
 
registerBlockType( 'another-read/stacks-block', {
    apiVersion: 2,
    title: 'Another Read Stacks',
    icon: 'book',
    category: 'common',

    attributes: {
        selectedStack: {
            type: 'number',
            default: null
        },
        numberOfBooks: {
            type: 'number',
            default: 1
        },
        jacketImage: {
            type: 'boolean',
            default: true
        },
        keynote: {
            type: 'boolean',
            default: true
        },
        authorLink: {
            type: 'boolean',
            default: true
        },
        bookLink: {
            type: 'boolean',
            default: true
        },

    
    },
    
    render_callback: 'stacksBlockOutput',

    edit: function ( {attributes, setAttributes} ) {

        //GET
        apiFetch( { path: '/wp/v2/stacks' } ).then( ( posts ) => {
            console.log(posts[0]['title']['rendered'])
            posts.forEach(postsFunction)
        } );

        console.log(attributes.selectedStack)

        var selectArray = [
            {
                disabled: true,
                label: 'Select an Option',
                value: ''
            },
        ]

        function postsFunction(post, index){
            console.log(selectArray)
            console.log(post['id'], index)
            selectArray.push(            
                {
                    label: post['title']['rendered'],
                    value: post['id'],
                }
            )
        }

        const blockProps = useBlockProps();
        return (
            <div { ...blockProps }>
                <InspectorControls key="settings">
                    <Panel>
                        <PanelBody>
                            <PanelRow>
                            <SelectControl
                                onChange={ ( event ) => setAttributes( {selectedStack: event})}
                                options={selectArray}
                                value={attributes.selectedStack}
                                />
                            </PanelRow>
                            <PanelRow>
                                <TextControl label="Number of books to show" type={('Input Type', 'number')} value={attributes.numberOfBooks} onChange={ ( event ) => setAttributes( {numberOfBooks: event})}></TextControl>
                            </PanelRow>
                            <PanelRow>
                                <CheckboxControl label="Display jacket image" checked={attributes.jacketImage} onChange={ ( event ) => setAttributes( {jacketImage: event})}></CheckboxControl>
                            </PanelRow>
                            <PanelRow>
                                <CheckboxControl label="Display keynote" checked={attributes.keynote} onChange={ ( event ) => setAttributes( {keynote: event})}></CheckboxControl>
                            </PanelRow>
                            <PanelRow>
                                <CheckboxControl label="Display author link" checked={attributes.authorLink} onChange={ ( event ) => setAttributes( {authorLink: event})}></CheckboxControl>
                            </PanelRow>
                            <PanelRow>
                                <CheckboxControl label="Display book link" checked={attributes.bookLink} onChange={ ( event ) => setAttributes( {bookLink: event})}></CheckboxControl>
                            </PanelRow>
                        </PanelBody>
                    </Panel>
                </InspectorControls>
                <div className='stacks-block-notice'>
                    <h3>Another Read Stacks </h3>
                    <p> Configure using the settings panel</p>
                </div>
                <ServerSideRender
                    block="another-read/stacks-block"
                    attributes={ attributes }
                />
            </div>
        );
    },
    save: () => {return null},
} )