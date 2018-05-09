import React from 'react';
import { Link } from 'react-router-dom'
import MoneyAmount from './MoneyAmount.jsx';

export default class CurrentAccount extends React.Component {
    constructor(props) {
        super(props)
        this.state = {
            id: null,
            name: '',
            total: 0,
            isLoaded: false,
            error: null
        }
    }

    componentDidMount() {
        const {match: {params}} = this.props;
        this.handleChange(params.id);
    }

    handleChange(id) {
        fetch('/api/v1/account/' + id)
            .then(res => res.json())
            .then(
                (result) => {
                    this.setState({isLoaded: true, id: result.account.id, name: result.account.name, total: result.account.total});
                },
                (error) => {
                    this.setState({isLoaded: true, error});
                }
            )
    }

    shouldComponentUpdate(nextProps, nextState) {
        if (this.state.isLoaded !== nextState.isLoaded) {
            return true;
        }

        if (nextProps.match.params.id != nextState.id) {
            this.setState({isLoaded: false});
            this.handleChange(nextProps.match.params.id);
            return true;
        }

        return false;
    }

    render() {
        if (this.state.error) {
            return <div>Error loading </div>;
        } else if (!this.state.isLoaded) {
            return <div>Loading ...</div>
        } else {
            return (
                <div className="current-account">
                    <span className="name">{this.state.name}</span>
                    <MoneyAmount amount={this.state.total} />
                </div>
            )
        }
    }
}
