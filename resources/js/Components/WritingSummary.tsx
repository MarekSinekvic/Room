import { Default, Writing } from "@/types";
import { Link, router } from "@inertiajs/react";
import { Divider, Grid2, Stack, ToggleButton } from "@mui/material";
import { useState } from "react";
import { FaRegBookmark } from "react-icons/fa";
import { SocialActions, SocialInfo } from "./SocialElements";

type Header = {writing:Writing,defaults:Default,width:string}
export default function ({writing,width='600px'}: Header) {
    
    return (
            <Stack maxWidth={'600px'} width={width} p={2} gap={1}>
                <Link href={('/writing/'+writing.id)}>
                    <Stack direction={'row'} justifyContent={'space-between'} fontSize={'12px'}>
                        <Stack width={'33%'} textAlign={'left'}>{new Date(writing.created_at).toDateString()}</Stack>
                        <Stack width={'33%'} textAlign={'center'}>{writing.user.name}</Stack>
                        <Stack width={'33%'} textAlign={'right'}>{writing.id}</Stack>
                    </Stack>
                    <Stack direction={'row'} justifyContent={'center'} textAlign={'center'} fontWeight={600} fontSize={24} px={3}>{writing.title}</Stack>
                    <Divider/>
                    <Stack direction={'row'} gap={1}>
                        <Stack direction={'row'} width={'60%'} textOverflow={"ellipsis"} overflow={'hidden'} height={'150px'}>{writing.content}...</Stack>
                        <img src={(writing.preview_image) ? 'storage/'+writing.preview_image.url : ''} width={'40%'} height={'auto'}></img>
                    </Stack>
                </Link>
                <Stack fontSize={'14px'} direction={'row'} alignItems={'center'} justifyContent={'space-between'}>
                    <SocialActions upvotes={writing.upvotes_sum_value} bookmarks={writing.bookmarks_count} onVote={(vote:number)=>{router.post(`/writing/${writing.id}/vote`, {state: vote})}}
                        onBookmark={()=>{router.post(`/writing/${writing.id}/bookmark`)}} defaults={{vote: typeof (writing.upvotes) !== 'undefined' && writing.upvotes.length > 0 ? writing.upvotes[0].value : 0, bookmark: typeof (writing.bookmarks) !== 'undefined' ? writing.bookmarks.length : false}}/>
                    <SocialInfo comments={writing.comments_count} views={writing.views_count}/>
                </Stack>
            </Stack>
    );
}