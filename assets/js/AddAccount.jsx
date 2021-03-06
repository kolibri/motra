import React from 'react';

export default class AddAccount extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            name: '',
        }

        this.handleInputChange = this.handleInputChange.bind(this);
        this.handleAdd = this.handleAdd.bind(this);
    }

    render() {
        return (
            <form onSubmit={this.handleAdd} className="transaction-box">
                <label>Name
                    <input type="text" name="name" value={this.state.name} onChange={this.handleInputChange}/>
                </label>
                <button type="submit">Submit</button>
            </form>
        );
    }

    handleInputChange(event) {
        const target = event.target;
        const value = target.type === 'checkbox' ? target.checked : target.value;
        const name = target.name;

        this.setState({
            [name]: value
        });
    }

    handleAdd(event) {
        event.preventDefault();
        console.log(this.props);
        fetch('/api/v1/account', {
            method: 'post',
            body: JSON.stringify({
                'name': this.state.name,
            })
        })
            .then(res => res.json())
            .then(
                (result) => {

                },
                (error) => {
                    this.setState({isLoaded: true, error});
                }
            )

    }
}
