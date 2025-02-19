import { Default, Writing } from "@/types";
import { router } from "@inertiajs/react";
import { Stack, ToggleButton, ToggleButtonGroup } from "@mui/material";
import { useState } from "react";
import { BsArrowDownShort, BsArrowUpShort } from "react-icons/bs";
import { FaRegBookmark, FaRegComments, FaRegEye } from "react-icons/fa";

type SocialActionsReact = {upvotes:number,bookmarks:number,onVote:Function,onBookmark:Function,defaults:Default};
function SocialActions({upvotes,bookmarks,onVote,onBookmark, defaults}:SocialActionsReact) {
    const [bookmarkSelect,setBookmarkSelect] = useState((typeof defaults.bookmark) == 'number' ? (defaults.bookmark == 1 ? true : false) : defaults.bookmark);
    const [vote,setVote] = useState(defaults.vote == 1 ? 'upvote' : (defaults.vote == -1 ? 'downvote' : ''));
    
    return (
        <Stack direction={'row'} gap={3}>
            {typeof (upvotes) !== 'undefined' ? (
                <Stack direction={'row'} alignItems={'center'} gap={0.5}>
                    <ToggleButtonGroup
                        value={vote}
                        exclusive
                        onChange={(e,nvote)=>{setVote(nvote); onVote(nvote)}}>
                        <ToggleButton size="small" color="primary" value={'upvote'}><BsArrowUpShort size={24} style={{margin:'-8px'}}/></ToggleButton>
                        <ToggleButton size="small" color="primary" value={'downvote'}><BsArrowDownShort size={24} style={{margin:'-8px'}}/> </ToggleButton>
                    </ToggleButtonGroup>
                    {upvotes ? upvotes : 0}
                </Stack>
            ) : ''}
            {typeof (bookmarks) !== 'undefined' ? (
                <Stack direction={'row'} alignItems={'center'} gap={0.5}>
                    <ToggleButton size="small" color="primary" value={'bookmark'} selected={bookmarkSelect} onChange={()=>{setBookmarkSelect(!bookmarkSelect); onBookmark()}}><FaRegBookmark/></ToggleButton>
                    {bookmarks}
                </Stack>    
            ) : ''}        
        </Stack>
    );
}
type SocialInfoReact = {comments:number,views:number};
function SocialInfo({comments,views}:SocialInfoReact) {
    // console.log(writing);
    
    return (
        <Stack direction={'row'} gap={3} justifyContent={'flex-end'}>
            <Stack direction={'row'} alignItems={'center'} gap={0.5}><FaRegComments/>{comments}</Stack>
            <Stack direction={'row'} alignItems={'center'} gap={0.5}><FaRegEye/>{views}</Stack>
        </Stack>
    );
}

export {SocialActions,SocialInfo};