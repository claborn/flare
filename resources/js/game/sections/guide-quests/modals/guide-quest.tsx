import React, { Fragment } from "react";
import Dialogue from "../../../components/ui/dialogue/dialogue";
import ComponentLoading from "../../../components/ui/loading/component-loading";
import { AxiosError, AxiosResponse } from "axios";
import Ajax from "../../../lib/ajax/ajax";
import Tabs from "../../../components/ui/tabs/tabs";
import TabPanel from "../../../components/ui/tabs/tab-panel";
import LoadingProgressBar from "../../../components/ui/progress-bars/loading-progress-bar";
import SuccessAlert from "../../../components/ui/alerts/simple-alerts/success-alert";
import DangerAlert from "../../../components/ui/alerts/simple-alerts/danger-alert";
import {
    guideQuestLabelBuilder,
    getRequirementKey,
    buildValueLink,
} from "../lib/guide-quest-label-builder";
import RequiredListItem from "../components/required-list-item";

export default class GuideQuest extends React.Component<any, any> {
    private tabs: { name: string; key: string }[];

    constructor(props: any) {
        super(props);

        this.tabs = [
            {
                key: "story",
                name: "Story",
            },
            {
                key: "instructions",
                name: "Instructions",
            },
        ];

        this.state = {
            loading: true,
            error_message: null,
            success_message: null,
            quest_data: null,
            can_hand_in: false,
            is_handing_in: false,
            completed_requirements: [],
        };
    }

    componentDidMount() {
        new Ajax()
            .setRoute("character/guide-quest/" + this.props.user_id)
            .doAjaxCall(
                "get",
                (result: AxiosResponse) => {
                    this.setState({
                        loading: false,
                        quest_data: result.data.quest,
                        can_hand_in: result.data.can_hand_in,
                        completed_requirements:
                            result.data.completed_requirements,
                    });
                },
                (error: AxiosError) => {
                    if (typeof error.response !== "undefined") {
                        const response = error.response;

                        this.setState({
                            error_message: response.data.message,
                            is_handing_in: false,
                        });
                    }
                }
            );
    }

    buildTitle() {
        if (this.state.loading) {
            return "One moment ...";
        }

        return this.state.quest_data.name;
    }

    closeMessage() {
        this.setState({
            success_message: null,
            error_message: null,
        });
    }

    handInQuest() {
        this.setState(
            {
                is_handing_in: true,
            },
            () => {
                new Ajax()
                    .setRoute(
                        "guide-quests/hand-in/" +
                            this.props.user_id +
                            "/" +
                            this.state.quest_data.id
                    )
                    .doAjaxCall(
                        "post",
                        (result: AxiosResponse) => {
                            this.setState({
                                is_handing_in: false,
                                quest_data: result.data.quest,
                                can_hand_in: result.data.can_hand_in,
                                success_message: result.data.message,
                            });
                        },
                        (error: AxiosError) => {}
                    );
            }
        );
    }

    fetchRequiredKeys(): string[] {
        return Object.keys(this.state.quest_data).filter((key: string) => {
            return (
                (key.startsWith("required_") || key.startsWith("secondary_")) &&
                this.state.quest_data[key] !== null
            );
        });
    }

    buildRequirementsList(): JSX.Element[] | [] {
        console.log(this.state.completed_requirements);
        const requirementsList: JSX.Element[] = [];

        this.fetchRequiredKeys().forEach((key: string) => {
            const label = guideQuestLabelBuilder(key, this.state.quest_data);

            if (label !== null) {
                const requiredKey = getRequirementKey(key);
                const value = this.state.quest_data[requiredKey];
                const completedRequirements = this.state.completed_requirements;

                const isFinished =
                    completedRequirements.includes(key) ||
                    completedRequirements.includes(requiredKey);

                requirementsList.push(
                    <RequiredListItem
                        key={key}
                        label={label}
                        isFinished={isFinished}
                        requirement={buildValueLink(
                            value,
                            key,
                            this.state.quest_data
                        )}
                    />
                );
            }
        });

        return requirementsList;
    }

    render() {
        return (
            <Dialogue
                is_open={this.props.is_open}
                handle_close={this.props.manage_modal}
                title={this.buildTitle()}
                secondary_actions={{
                    secondary_button_label: "Hand in",
                    secondary_button_disabled: !this.state.can_hand_in,
                    handle_action: this.handInQuest.bind(this),
                }}
                large_modal={false}
                primary_button_disabled={this.state.action_loading}
            >
                {this.state.loading && this.state.quest_data === null ? (
                    <div className="p-5 mb-2">
                        <ComponentLoading />
                    </div>
                ) : (
                    <Fragment>
                        {this.state.success_message !== null ? (
                            <SuccessAlert
                                close_alert={this.closeMessage.bind(this)}
                            >
                                {this.state.success_message}
                            </SuccessAlert>
                        ) : null}

                        {this.state.error_message !== null ? (
                            <DangerAlert
                                close_alert={this.closeMessage.bind(this)}
                            >
                                {this.state.error_message}
                            </DangerAlert>
                        ) : null}
                        <div className={"mt-2"}>
                            <div className="grid md:grid-cols-2 gap-2">
                                <div>
                                    <h3 className="mb-2">
                                        Required to complete
                                    </h3>
                                    <ul className="list-disc ml-[18px]">
                                        {this.state.quest_data
                                            .required_level !== null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Level your character to:{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_level
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data
                                            .required_game_map_id !== null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Get Access to:{" "}
                                                {
                                                    this.state.quest_data
                                                        .game_map_name
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data.quest_name !==
                                        null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Complete the quest:{" "}
                                                {
                                                    this.state.quest_data
                                                        .quest_name
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data
                                            .required_quest_item_id !== null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Get Quest Item:{" "}
                                                {
                                                    this.state.quest_data
                                                        .quest_item_name
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data
                                            .secondary_quest_item_id !==
                                        null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Get Secondary Quest Item:{" "}
                                                {
                                                    this.state.quest_data
                                                        .secondary_quest_item_name
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data.skill_name !==
                                        null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Get Skill:{" "}
                                                {
                                                    this.state.quest_data
                                                        .skill_name
                                                }{" "}
                                                to level:{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_skill_level
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data
                                            .required_secondary_skill !==
                                        null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Get Secondary Skill:{" "}
                                                {
                                                    this.state.quest_data
                                                        .secondary_skill_name
                                                }{" "}
                                                to level:{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_secondary_skill_level
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data
                                            .skill_type_name !== null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Get Skill Type:{" "}
                                                {
                                                    this.state.quest_data
                                                        .skill_type_name
                                                }{" "}
                                                to level:{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_skill_type_level
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data.faction_name !==
                                        null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Get Faction:{" "}
                                                {
                                                    this.state.quest_data
                                                        .faction_name
                                                }{" "}
                                                to level:{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_faction_level
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data
                                            .required_mercenary_type !==
                                        null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Get Mercenary:{" "}
                                                {
                                                    this.state.quest_data
                                                        .mercenary_name
                                                }{" "}
                                                to level{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_mercenary_level
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data
                                            .required_secondary_mercenary_type !==
                                        null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Get Secondary Mercenary:{" "}
                                                {
                                                    this.state.quest_data
                                                        .secondary_mercenary_name
                                                }{" "}
                                                to level{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_secondary_mercenary_level
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data
                                            .required_class_specials_equipped !==
                                        null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Equip # of Class Specials:{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_class_specials_equipped
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data
                                            .required_kingdoms !== null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Required Kingdom #:{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_kingdoms
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data
                                            .required_kingdom_level !== null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Required Buildings Level
                                                (Combined):{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_kingdom_level
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data
                                            .required_kingdom_units !== null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Required Units Amount
                                                (Combined):{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_kingdom_units
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data.passive_name !==
                                        null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Get Passive Skill:{" "}
                                                {
                                                    this.state.quest_data
                                                        .passive_name
                                                }{" "}
                                                to level:{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_passive_skill
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data
                                            .required_stats !== null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Get all stats to:{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_stats
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data.required_str !==
                                        null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Get STR to:{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_str
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data.required_dex !==
                                        null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Get DEX to:{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_dex
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data.required_agi !==
                                        null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Get AGIto:{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_agi
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data.required_int !==
                                        null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Get INT to:{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_int
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data.required_dur !==
                                        null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Get DUR to:{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_dur
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data.required_chr !==
                                        null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Get CHR to:{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_chr
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data
                                            .required_focus !== null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Get FOCUS to:{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_focus
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data.required_gold !==
                                        null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Obtain Gold Amount:{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_gold
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data
                                            .required_shards !== null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Obtain Shards Amount:{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_shards
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data
                                            .required_gold_dust !== null ? (
                                            <li
                                                className={
                                                    "text-orange-600 dark:text-orange-400"
                                                }
                                            >
                                                Obtain Gold Dust Amount:{" "}
                                                {
                                                    this.state.quest_data
                                                        .required_gold_dust
                                                }
                                            </li>
                                        ) : null}
                                    </ul>
                                    <h4 className="mt-4"> Testing </h4>
                                    <ul className="my-4 list-disc ml-[18px]">
                                        {this.buildRequirementsList()}
                                    </ul>
                                </div>
                                <div className="block md:hidden border-b-2 border-b-gray-300 dark:border-b-gray-600 my-3"></div>
                                <div>
                                    <h3 className="mb-2">Rewards</h3>
                                    <ul className="list-disc ml-[18px]">
                                        {this.state.quest_data.xp_reward !==
                                        null ? (
                                            <li
                                                className={
                                                    "text-green-600 dark:text-green-400"
                                                }
                                            >
                                                Xp Reward:{" "}
                                                {
                                                    this.state.quest_data
                                                        .xp_reward
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data.gold_reward !==
                                        null ? (
                                            <li
                                                className={
                                                    "text-green-600 dark:text-green-400"
                                                }
                                            >
                                                Gold Reward:{" "}
                                                {
                                                    this.state.quest_data
                                                        .gold_reward
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data
                                            .gold_dust_reward !== null ? (
                                            <li
                                                className={
                                                    "text-green-600 dark:text-green-400"
                                                }
                                            >
                                                Gold Dust Reward:{" "}
                                                {
                                                    this.state.quest_data
                                                        .gold_dust_reward
                                                }
                                            </li>
                                        ) : null}
                                        {this.state.quest_data.shards_reward !==
                                        null ? (
                                            <li
                                                className={
                                                    "text-green-600 dark:text-green-400"
                                                }
                                            >
                                                Shards Reward:{" "}
                                                {
                                                    this.state.quest_data
                                                        .shards_reward
                                                }
                                            </li>
                                        ) : null}
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div className="border-b-2 border-b-gray-300 dark:border-b-gray-600 my-3"></div>

                        {this.state.quest_data.faction_points_per_kill !==
                        null ? (
                            <p className="text-blue-700 dark:text-blue-400">
                                You have been given an additional{" "}
                                {this.state.quest_data.faction_points_per_kill}{" "}
                                Faction Points per kill for this quest.
                            </p>
                        ) : null}

                        <Tabs tabs={this.tabs}>
                            <TabPanel key={"story"}>
                                <div
                                    className={
                                        "border-1 rounded-sm p-3 bg-slate-300 dark:bg-slate-700 max-h-[250px] overflow-x-auto mb-4"
                                    }
                                >
                                    <div
                                        dangerouslySetInnerHTML={{
                                            __html: this.state.quest_data
                                                .intro_text,
                                        }}
                                    />
                                </div>
                            </TabPanel>
                            <TabPanel key={"instructions"}>
                                <div
                                    className={
                                        "border-1 rounded-sm p-3 bg-slate-300 dark:bg-slate-700 max-h-[250px] overflow-x-auto mb-4 guide-quest-instructions"
                                    }
                                >
                                    <div
                                        dangerouslySetInnerHTML={{
                                            __html: this.state.quest_data
                                                .instructions,
                                        }}
                                    />
                                </div>
                            </TabPanel>
                        </Tabs>
                        <p className={"mt-4 mb-4"}>
                            The Hand in button will become available when you
                            meet the requirements. Unless exploration is
                            running.
                        </p>
                        {this.state.is_handing_in ? (
                            <LoadingProgressBar />
                        ) : null}
                    </Fragment>
                )}
            </Dialogue>
        );
    }
}
