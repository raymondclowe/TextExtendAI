// Version: 1.1 dev

function nextParaAI() {
    console.log("Start NextParaAI")
    // Define the API endpoint URL
    const apiUrl = 'https://api.mistral.ai/v1/chat/completions';

    // Check if API token is in localStorage
    let MistralApiToken = localStorage.getItem('mistralAPIKey');

    if (!MistralApiToken) {

        // Token not found, show popup to get it from user

        let popup = document.createElement('div');
        popup.style.position = 'fixed';
        popup.style.top = '50%';
        popup.style.left = '50%';
        popup.style.transform = 'translate(-50%, -50%)';

        let form = document.createElement('form');
        let input = document.createElement('input');
        input.type = 'text';
        input.placeholder = 'Enter Mistral API token';

        let submitBtn = document.createElement('button');
        submitBtn.textContent = 'Submit';

        form.appendChild(input);
        form.appendChild(submitBtn);

        popup.appendChild(form);

        document.body.appendChild(popup);

        form.addEventListener('submit', e => {
            e.preventDefault();
            MistralApiToken = input.value;
            localStorage.setItem('mistralAPIKey', MistralApiToken);
            popup.remove();
        });

    }

    // Now apiToken is either from localStorage or was just entered




    // Get the currently selected block
    const selectedBlock = wp.data.select('core/block-editor').getSelectedBlock();

    // Get the preceding blocks
    const blocks = wp.data.select('core/block-editor').getBlocks();
    const selectedBlockIndex = blocks.findIndex((block) => block.clientId === selectedBlock.clientId);
    const precedingBlocks = blocks.slice(0, selectedBlockIndex + 1);

    // Extract the content from the preceding blocks
    content = '';
    precedingBlocks.forEach((block) => {
        if (block.name === 'core/heading') {
            const level = block.attributes.level;
            content += '#'.repeat(level) + ' ' + block.attributes.content + '\n\n';
        } else if (block.name === 'core/paragraph') {
            content += block.attributes.content + '\n\n';
        } else if (block.name === 'core/list') {
            const ordered = block.attributes.ordered;
            const innerBlocks = block.innerBlocks;
            innerBlocks.forEach((innerBlock) => {
                content += ordered ? '1. ' : '- ';
                content += innerBlock.attributes.content + '\n';
            });

        }
    });

    // Get the title 
    const title = '# ' + wp.data.select("core/editor").getEditedPostAttribute('title') + '\n\n';

    // Concatenate the title and content
    const articleSoFar = title + content;


    // * Original instruction:
    // 
    // const instruction = `Here is the beginning of a blog post. 
    // You should write the next one single paragraph, do not repeat the existing paragraph but continue 
    // on with the continuation. Do not include line breaks or headers, just a paragraph of prose that 
    // continues this blog post in the same style. It could either be an expansion or sensible next 
    // topic based on what has been written already, or it could be a transition into a new related topic.
    // 
    // Here is the blog post so far, you will write the next paragraph.
    // 
    // `

    // * Optimized by ChatGPT4 prompt, may be too brief
    const instructionText = `Continue the following blog post with a paragraph that:

    1. Matches the original style and tone.

    2. Either delves deeper into the topic or introduces a related one, ensuring it flows seamlessly from the existing writing.

    3. Engages the reader by adding new insights or perspectives without repeating provided information.

    Ensure your writing is coherent and captivates the target audience, enriching the blog post.

    Write only the continuation, making sure it follows smoothly from and after the existing paragraphs.

    Existing post starts below, starting with the title:

>`

    // Define the prompt template
    const promptTemplate = `<s>[INST] {prompt} [/INST]`;

    contentString = promptTemplate.replace('{prompt}', instructionText + articleSoFar);

    // Define the data payload for the API request
    const data = {
        model: 'mistral-tiny',
        messages: [
            {
                role: 'user',
                content: contentString
            }
        ],
        temperature: 0.6,
        max_tokens: 1024,
        top_p: 0.9,
        // top_k: 50,
        stream: false,
        // unsafe_prompt: false,
        random_seed: null
    };

    // Make the API request
    fetch(apiUrl, {
        method: 'POST',
        headers: {
            'Authorization': `Bearer ${MistralApiToken}`,
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify(data)
    })
        .then(response => response.json())
        .then(responseData => {
            // Extract the generated text from the API response
            const generatedText = responseData.choices[0].message.content.trim();

            // Create a new paragraph block
            const newParagraphBlock = wp.blocks.createBlock('core/paragraph', {
                content: generatedText
            });



            // Add the new block to the editor
            wp.data.dispatch('core/block-editor').insertBlocks([newParagraphBlock], selectedBlockIndex + 1);
            // wp.data.dispatch('core/block-editor').insertBlocksAfter(newParagraphBlock, selectedBlock.clientId);
        })
        .catch(error => {
            console.error('An error occurred:', error);
        });
};


document.addEventListener('keydown', (event) => {
    if (event.ctrlKey && event.shiftKey && event.key === 'E') {
        event.preventDefault();
        nextParaAI();
    }
});
