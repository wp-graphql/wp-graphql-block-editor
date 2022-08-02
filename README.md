# WPGraphQL Block Editor

This is an experimental plugin to work toward compatiblity between the WordPress Gutenberg Block 
Editor and WPGraphQL, based on Server Side registration of Gutenberg Blocks.

This stems from this blog post: https://www.wpgraphql.com/2021/03/09/gutenberg-and-decoupled-applications/

And this Github issue: https://github.com/wp-graphql/wp-graphql/issues/1764

## BETA NOTICE

This plugin is an experimental beta plugin. It is not officially supported at the moment and will have breaking changes as it progresses. Use at your own risk.

## Problems

### Server Awareness
Gutenberg is a JavaScript application where Blocks are registered in JavaScript and the WordPress
server doesn't know what a block is, what attributes a block _can_ have, etc. 

Without server awareness of Gutenberg blocks, APIs such as WP-CLI, the WP REST API and WPGraphQL can't
expose blocks in a scalable way for decoupled clients to interact with. 

### Query Blocks as Data
Developers that are working with WordPress in a decoupled context, with WPGraphQL specifically, have
expressed a desire to want to query Gutenberg Blocks as data, then use the data in Component-based
architectures with Next, Vue, React Native, and similar. 

Instead of querying a block of unpredictable markup and parsing it, or querying unpredictable JSON and parsing it, 
developers want to see what Blocks are available as GraphQL Types, then specify the exact fields
they want for their components.

### Server Validation

Currently, since blocks are largely client-side only and the server doesn't know about their existence, 
the server isn't able to validate input from the client before the data is saved. 

I don't think we'll be able to solve this for Gutenberg via this plugin, but we _might_ be able to solve
it for decoupled clients interested in using GraphQL Mutations to post data back to WordPress
and modify blocks.
