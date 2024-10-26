from flask import Flask, request, jsonify
from transformers import LlamaTokenizer, LlamaForCausalLM

app = Flask(__name__)

# Load the model and tokenizer
model_name = "meta-llama/Llama-7B"
tokenizer = LlamaTokenizer.from_pretrained(model_name)
model = LlamaForCausalLM.from_pretrained(model_name)

@app.route('/chat', methods=['POST'])
def chat():
    user_input = request.json.get('message')
    inputs = tokenizer(user_input, return_tensors="pt")
    outputs = model.generate(**inputs, max_length=100)
    response = tokenizer.decode(outputs[0], skip_special_tokens=True)
    return jsonify({'response': response})

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
