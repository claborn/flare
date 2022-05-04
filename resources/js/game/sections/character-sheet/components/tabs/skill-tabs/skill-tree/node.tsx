import React from "react";
import ManualProgressBar from "../../../../../../components/ui/progress-bars/manual-progress-bar";
import TimerProgressBar from "../../../../../../components/ui/progress-bars/timer-progress-bar";
import clsx from "clsx";

export default class Node extends React.Component<any, any> {
    constructor(props: any) {
        super(props);
    }

    isMaxLevel() {
        return this.props.passive.current_level === this.props.passive.max_level;
    }

    render() {
        return (
            <div>
                <button onClick={() => this.props.show_passive_modal(this.props.passive)}>
                    <h4 className={clsx({
                        'text-red-500 dark:text-red-400' : this.props.passive.is_locked,
                    })}>{this.props.passive.name}
                    </h4>
                </button>
                <p className='mt-3'>Level: {this.props.passive.current_level}/{this.props.passive.max_level}</p>
                {
                    !this.isMaxLevel() ?
                        <p className='mt-3'>Hours till next: {this.props.passive.hours_to_next}</p>
                    :
                        <p className='text-green-700 dark:text-green-600 mt-3'>Skill is maxed out!</p>
                }
            </div>
        );
    }
}